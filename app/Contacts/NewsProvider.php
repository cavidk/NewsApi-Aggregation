<?php

namespace App\Contacts;


interface NewsProvider
{
    public function fetchNews();
    public function search($query);
    public function dbSearch($query);
    public function saveArticle($articles);

}
