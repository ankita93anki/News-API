<?php
// File: app/Services/NewsAggregator.php

namespace App\Services;

class NewsAggregator
{
    protected $services;

    public function __construct(NewsApiService $newsApi, OpenNewsService $openNews, BbcNewsService $bbcNews)
    {
        $this->services = [$newsApi, $openNews, $bbcNews];
    }

    public function fetchAndStoreArticles()
    {
        foreach ($this->services as $service) {
            try {
                $service->fetchArticles();
            } catch (\Exception $e) {
                logger()->error("Error fetching articles from service: " . get_class($service) . ' | ' . $e->getMessage());
            }
        }
    }
}