<?php

namespace App\Contacts;

interface NewYorkTimesInterface
{
    public function fetchNews($category);
    public function search($query);
    public function dbSearch($query);
    public function saveArticle($articles);


}
