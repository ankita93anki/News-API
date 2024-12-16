<?php

// File: app/Services/NewsApiFetcher.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

abstract class NewsApiFetcher
{
    protected $baseUrl;
    protected $apiKey;
    protected $defaultParams;

    public function __construct($baseUrl, $apiKey, $defaultParams = [])
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->defaultParams = $defaultParams;
    }

    protected function fetchData($endpoint = '', $params = [])
    {
        $response = Http::get($this->baseUrl . $endpoint, array_merge(['apiKey' => $this->apiKey], $this->defaultParams, $params));

        if ($response->failed()) {
            throw new \Exception("Failed to fetch data from API: " . $this->baseUrl);
        }

        return $response->json();
    }

    abstract public function fetchArticles();
}