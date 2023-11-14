<?php

namespace App\Providers\News;

use App\Contacts\NewsProvider;
use App\Models\GuardianNews;
use App\Models\News;
use Faker\Provider\UserAgent;

class GuardianProvider implements NewsProvider
{

    private $source = 'newsguardian';

    private $api_key = '023525dc-3298-452c-9cc5-cbebec96ffe5';


    public function fetchNews()
    {
        return GuardianNews::where('source', $this->source)
            ->get();
    }

    public function search($query)
    {
        if (!$query) {
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

    public function importSections($sectionName)
    {
        if (isset($sectionName['sectionName']) && count($sectionName['sectionName'])) {
            foreach ($sectionName['sectionName'] as $section) {
                $this->saveArticle($section);
            }
        }
    }


    public function saveArticle($sectionName)
    {

        try {
            GuardianNews::updateOrCreate([
                'id' => $sectionName['id'],
                'type' => $sectionName['type'],
                'webPublicationDate' => $sectionName['webPublicationDate'],
                'webTitle' => $sectionName['webTitle'],
                'webUrl' => $sectionName['webUrl'],
                'apiUrl' => $sectionName['apiUrl'],
                'isHosted' => $sectionName['isHosted'],
                'pillarId' => $sectionName['pillarId'],
                'pillarName' => $sectionName['pillarName'],
            ]);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

    }
}
