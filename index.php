<?php

declare(strict_types=1);

use Google\CloudFunctions\FunctionsFramework;
use InstapaperFetcher\Module\AppModule;
use InstapaperFetcher\Service\BookmarkFetchService;
use Koriym\EnvJson\EnvJson;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\Injector;

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$envJson = new EnvJson(__DIR__);
$envJson->load('env.json');

// Cloud Run function entry point
FunctionsFramework::http('fetchBookmarks', function (ServerRequestInterface $request) {
    try {
        // Get parameters from request
        $params = $request->getQueryParams();
        $limit = isset($params['limit']) ? (int)$params['limit'] : 25;
        $fetchText = isset($params['fetch_text']) && $params['fetch_text'] === 'true';
        
        // Create DI container and get service
        $injector = new Injector(new AppModule());
        $service = $injector->getInstance(BookmarkFetchService::class);
        
        // Fetch and store bookmarks
        $count = $service->fetchAndStore($limit, $fetchText);
        
        return new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'success' => true,
                'message' => "Fetched and stored {$count} bookmarks",
                'count' => $count
            ])
        );
    } catch (\Exception $e) {
        error_log('Error fetching bookmarks: ' . $e->getMessage());
        
        return new \GuzzleHttp\Psr7\Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ])
        );
    }
});