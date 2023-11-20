<?php

namespace App\Providers\News;

use App\Contacts\NewsProvider;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Carbon\Carbon;


class GuardianProvider implements NewsProvider
{

    private $source = 'guardian';

    protected $api_key;

    /**
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = env('GUARDIAN_API_KEY');
    }


    public function fetchNews()
    {
        return News::where('source', $this->source)
            ->get();
    }

    public function search($query)
    {
        if (!$query) {
            return $this->fetchNews();
        }
        return $this->searchRequest($query);
    }

    public function dbSearch($q)
    {
        $result = News::
            where('source', $this->source)
            ->where(function($query) use ($q){
                $query->where('title','LIKE','%'.$q.'%')
                    ->orWhere('content','LIKE','%'.$q.'%');
            })->get();
        return NewsResource::collection($result);
    }

    public function searchRequest($query)
    {
        $from = date('Y-m-d');
        $url = 'https://content.guardianapis.com/search?q=' . $query . '&from=' . $from . '&api-key=' . $this->api_key;
        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => $agent,
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

        $result = json_decode($response, 1);

        $this->importSections($result['response']['results']);

    }

    public function importSections($data)
    {
        foreach ($data as $item) {
            $this->saveArticle($item);
        }
    }


    public function saveArticle($article)
    {

        try {
            News::updateOrCreate([
                'title' => $article['sectionName'],
                'source' => $this->source
            ],
            [
                'title' => $article['sectionName'],
                'content' => $article['webTitle'],
                'source' => $this->source,
                'img_url' => $article['urlToImage'] ?? '',
                'publishedAt' => Carbon::parse($article['webPublicationDate']),
            ]);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

    }
}
