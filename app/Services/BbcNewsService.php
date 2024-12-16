<?php

// File: app/Services/BbcNewsService.php

namespace App\Services;

use App\Models\Article;

class BbcNewsService extends NewsApiFetcher
{
    public function __construct()
    {
        parent::__construct(
            config('newsapis.apis.bbcnews.url'),
            config('newsapis.apis.bbcnews.key'),
            config('newsapis.apis.bbcnews.params')
        );
    }

    public function fetchArticles()
    {
        $data = $this->fetchData();
        foreach ($data['news'] as $news) { // Adjust key based on API structure
            Article::updateOrCreate(
                ['title' => $news['title']],
                [
                    'content' => $news['description'] ?? null,
                    'source' => 'Media Stack News',
                    'category' => $news['category'] ?? 'General',
                    'author' => $news['author'] ?? 'Unknown',
                    'published_at' => $news['published'] ?? now(),
                ]
            );
        }
    }
}