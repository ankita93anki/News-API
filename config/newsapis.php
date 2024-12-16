<?php

// File: config/newsapis.php

return [
    'apis' => [
        'newsapi' => [
            'url' => 'https://newsapi.org/v2/top-headlines',
            'key' => env('NEWS_API_KEY'),
            'params' => [
                'country' => 'us',
                'category' => 'general',
            ],
        ],
        'opennews' => [
            'url' => 'https://api.nytimes.com/svc/mostpopular/v2/viewed',
            'key' => env('NEW_YORK_NEWS_API_Key'),
            'params' => [
                'language' => 'en',
            ],
        ],
        'bbcnews' => [
            'url' => 'https://api.mediastack.com/news',
            'key' => env('MEDIA_STACK_API_KEY'),
            'params' => [
                'country' => 'in'
            ],
        ],
    ],
];