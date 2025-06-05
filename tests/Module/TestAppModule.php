<?php

declare(strict_types=1);

namespace InstapaperFetcher\Tests\Module;

use Google\Cloud\Storage\StorageClient;
use InstapaperFetcher\Contracts\InstapaperClientInterface;
use InstapaperFetcher\Tests\Fake\FakeInstapaperClient;
use InstapaperFetcher\Tests\Fake\FakeStorageClient;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ray\Di\AbstractModule;

final class TestAppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Bind test implementations
        $this->bind(InstapaperClientInterface::class)->to(FakeInstapaperClient::class);
        $this->bind(StorageClient::class)->toInstance(new FakeStorageClient());
        $this->bind(LoggerInterface::class)->to(NullLogger::class);
        $this->bind('')->annotatedWith('storage.bucket')->toInstance('test-bucket');
    }
}