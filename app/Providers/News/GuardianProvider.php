<?php

namespace App\Providers\News;

use App\Contacts\NewsProvider;
use App\Models\GuardianNews;

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

    }

    public function dbSearch($query)
    {
        // TODO: Implement dbSearch() method.
    }





    public function saveArticle($articles)
    {
        // TODO: Implement saveArticle() method.
    }
}
