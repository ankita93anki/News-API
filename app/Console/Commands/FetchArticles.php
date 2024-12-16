<?php
// File: app/Console/Commands/FetchArticles.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregator;

class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';
    protected $description = 'Fetch articles from multiple news APIs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(NewsAggregator $aggregator)
    {
        $aggregator->fetchAndStoreArticles();
        $this->info('Articles fetched and stored successfully!');
    }
}