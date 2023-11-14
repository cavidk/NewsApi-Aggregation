<?php

namespace App\Providers\News;

use App\Contacts\NewsProvider;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Carbon\Carbon;

class ApiOrgProvider implements NewsProvider
{

    private $source = 'newsapiorg';
    private $api_key = '6d24c2d2b72f445da028314bbbe09d28';

    public function fetchNews()
    {
        return News::where('source', $this->source)
            ->get();
    }

    public function search($q)
    {
        if(!$q){
            return $this->fetchNews();
        }
        return $this->searchRequest($q);
    }

    public function dbSearch($q){
        $result = News::
            orWhere('title','LIKE','%'.$q.'%')
            ->orWhere('content','LIKE','%'.$q.'%')
            ->where('source', $this->source)
            ->get();
        return NewsResource::collection($result);
    }

    public function searchRequest($q)
    {
        $from = date('Y-m-d');
        $url = 'https://newsapi.org/v2/everything?q=' . $q . '&from='.$from.'sortBy=popularity&apiKey='.$this->api_key;
//        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
//            CURLOPT_USERAGENT => $agent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response,1);

        $this->importArticles($result);
        return $this->dbSearch($q);
    }

    public function importArticles($articles)
    {
        if(isset($articles['articles']) && count($articles['articles'])){
            foreach ($articles['articles'] as $article) {
                $this->saveArticle($article);
            }
        }
    }

    public function saveArticle(mixed $article)
    {
        try {
            News::updateOrCreate([
                'title' => $article['title'],
                'source' => $this->source
            ], [
                'title' => $article['title'],
                'content' => $article['content'],
                'source' => $this->source,
                'img_url' => $article['urlToImage'],
                'publishedAt' => Carbon::parse($article['publishedAt']),
            ]);
        } catch (\Exception $e){
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
