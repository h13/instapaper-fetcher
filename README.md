# Instapaper Fetcher

A microservice that fetches bookmarks from Instapaper using OAuth authentication.

## Overview

This service is part of the Instapaper-to-Podcast pipeline. It authenticates with Instapaper's API and fetches bookmarked articles, storing them in Google Cloud Storage for processing by downstream services.

## Requirements

- PHP 8.3+
- Composer
- Google Cloud Storage access
- Instapaper API credentials (OAuth)

## Installation

```bash
composer install
```

## Configuration

Create an `.env` file based on the `env.schema.json`:

```json
{
  "APP_ENV": "development",
  "APP_DEBUG": true,
  "INSTAPAPER_CONSUMER_KEY": "your-consumer-key",
  "INSTAPAPER_CONSUMER_SECRET": "your-consumer-secret",
  "INSTAPAPER_ACCESS_TOKEN": "your-access-token",
  "INSTAPAPER_ACCESS_TOKEN_SECRET": "your-access-token-secret",
  "GCP_PROJECT_ID": "your-project-id",
  "STORAGE_BUCKET_NAME": "your-bucket-name",
  "GOOGLE_APPLICATION_CREDENTIALS": "/path/to/credentials.json"
}
```

## Usage

### CLI

```bash
php bin/app.php /bookmarks/fetch
```

### HTTP API

```bash
# Start the server
php -S localhost:8080 -t public/

# Fetch bookmarks
curl http://localhost:8080/bookmarks/fetch
```

## Testing

```bash
composer test
```

## Docker

```bash
docker build -t instapaper-fetcher .
docker run -v /path/to/.env:/app/.env instapaper-fetcher
```

## Architecture

This service uses the BEAR.Sunday framework with:
- Resource-oriented architecture
- Dependency injection with Ray.Di
- OAuth authentication for Instapaper API
- Google Cloud Storage for output

## License

MIT License