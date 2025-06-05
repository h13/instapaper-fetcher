<?php

declare(strict_types=1);

namespace InstapaperFetcher\Module;

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use InstapaperFetcher\Contracts\InstapaperClientInterface;
use InstapaperFetcher\Service\InstapaperClient;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Logger binding
        $this->bind(LoggerInterface::class)->toProvider(LoggerProvider::class)->in(Scope::SINGLETON);
        
        // HTTP Client binding
        $this->bind(Client::class)->toInstance(new Client());
        
        // Instapaper configuration
        $this->bind('')->annotatedWith('instapaper.config')->toInstance([
            'consumer_key' => $_ENV['INSTAPAPER_CONSUMER_KEY'] ?? '',
            'consumer_secret' => $_ENV['INSTAPAPER_CONSUMER_SECRET'] ?? '',
            'access_token' => $_ENV['INSTAPAPER_ACCESS_TOKEN'] ?? '',
            'access_token_secret' => $_ENV['INSTAPAPER_ACCESS_TOKEN_SECRET'] ?? '',
        ]);
        
        // Instapaper client binding
        $this->bind(InstapaperClientInterface::class)->to(InstapaperClient::class)->in(Scope::SINGLETON);
        
        // Storage configuration
        $this->bind('')->annotatedWith('storage.bucket')->toInstance(
            $_ENV['STORAGE_BUCKET_NAME'] ?? ''
        );
        
        // Storage client binding
        $this->bind(StorageClient::class)->toProvider(StorageClientProvider::class)->in(Scope::SINGLETON);
    }
}

final class LoggerProvider implements \Ray\Di\ProviderInterface
{
    public function get(): LoggerInterface
    {
        $logger = new Logger('instapaper-fetcher');
        $logger->pushHandler(new StreamHandler('php://stderr', Logger::INFO));
        return $logger;
    }
}

final class StorageClientProvider implements \Ray\Di\ProviderInterface
{
    public function get(): StorageClient
    {
        $config = [];
        if (isset($_ENV['GCP_PROJECT_ID'])) {
            $config['projectId'] = $_ENV['GCP_PROJECT_ID'];
        }
        if (isset($_ENV['GOOGLE_APPLICATION_CREDENTIALS'])) {
            $config['keyFilePath'] = $_ENV['GOOGLE_APPLICATION_CREDENTIALS'];
        }
        return new StorageClient($config);
    }
}