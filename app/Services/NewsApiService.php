<?php
// File: app/Services/NewsApiService.php

namespace App\Services;

use App\Models\Article;

class NewsApiService extends NewsApiFetcher
{
    public function __construct()
    {
        parent::__construct(
            config('newsapis.apis.newsapi.url'),
            config('newsapis.apis.newsapi.key'),
            config('newsapis.apis.newsapi.params')
        );
    }

    public function fetchArticles()
    {
        $data = $this->fetchData();
        foreach ($data['articles'] as $news) {
            Article::updateOrCreate(
                ['title' => $news['title']],
                [
                    'content' => $news['content'],
                    'source' => $news['source']['name'] ?? 'Unknown',
                    'category' => $news['category'] ?? 'General',
                    'author' => $news['author'] ?? 'Unknown',
                    'published_at' => $news['publishedAt'] ?? now(),
                ]
            );
        }
    }
}