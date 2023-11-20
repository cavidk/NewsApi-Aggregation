<?php

namespace App\Providers\News;

use App\Contacts\NewYorkTimesInterface;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimesProvider implements NewYorkTimesInterface
{
    private $source = 'new_york_times';
    protected $apiKey;


    public function __construct()
    {
        $this->apiKey = env('NY_TIMES_API_KEY');
    }

    public function fetchNews($category)
    {
        $response = Http::get("https://api.nytimes.com/svc/topstories/v2/{$category}.json", [
            'api-key' => $this->apiKey,
        ]);
        if ($response->successful()) {
            return $response->json()['results'];
        }
        return [];
    }

    public function search($query)
    {
        if(!$query){
            return $this->fetchNews();
        }
        return $this->searchRequest($query);
    }

    public function dbSearch($query)
    {
        // TODO: Implement dbSearch() method.
    }

    public function searchRequest($query)
    {
        $from = date('Y-m-d');
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json?q' . $query . '&from='.$from.'sortBy=popularity&apiKey='.$this->apiKey;
        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nytimes.com/svc/search/v2/articlesearch.json?q=election&api-key=hE0mu2PAb0BPqSm9oyKn3GHPMziPk3Ww',
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

        $this->importNews($result);
        return $this->dbSearch($query);
    }

    public function importNews($category)
    {
        $articles = $this->fetchNews($category);
        foreach ($articles as $article) {
            $this->saveArticle($article);
        }
    }

    public function saveArticle($article)
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
