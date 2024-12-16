<?php
// File: app/Services/OpenNewsService.php

namespace App\Services;

use App\Models\Article;

class OpenNewsService extends NewsApiFetcher
{
    public function __construct()
    {
        parent::__construct(
            config('newsapis.apis.opennews.url'),
            config('newsapis.apis.opennews.key'),
            config('newsapis.apis.opennews.params')
        );
    }

    public function fetchArticles()
    {
        $data = $this->fetchData();
        foreach ($data['data'] as $news) { // Adjust key based on API structure
            Article::updateOrCreate(
                ['title' => $news['title']],
                [
                    'content' => $news['abstract'] ?? null,
                    'source' => 'New York Times',
                    'category' => $news['subsection'] ?? 'General',
                    'author' => 'NewYork Times Writer',
                    'published_at' => $news['published_date'] ?? now(),
                ]
            );
        }
    }
}