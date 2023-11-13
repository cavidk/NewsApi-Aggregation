<?php

namespace App\Providers\News;

use App\Contacts\NewsProvider;

class GuardianProvider implements NewsProvider
{

    private $source = 'newsguardian';

    private $api_key = '';


    public function fetchNews()
    {

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
