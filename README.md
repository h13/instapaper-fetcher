# Instapaper Fetcher

A Cloud Run Functions service that fetches bookmarks from Instapaper using OAuth authentication and stores them in Google Cloud Storage.

## Overview

This service authenticates with Instapaper's API and fetches bookmarked articles, storing them in Google Cloud Storage for processing by downstream services.

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

Create an `env.json` file in the project root based on the `env.schema.json`:

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

## Local Development

```bash
# Start local server
composer start

# Run tests
composer test

# Check code style
composer cs

# Fix code style
composer cs-fix

# Run static analysis
composer sa

# Build project (runs all quality checks)
composer build
```

## Testing

The project uses PHPUnit for testing with comprehensive quality tools:

```bash
# Run unit tests
composer test

# Generate coverage report
composer coverage

# Run all quality checks
composer tests
```

## Deployment to Cloud Run

```bash
# Deploy using Cloud Build
gcloud builds submit --config cloudbuild.yaml
```

## API Usage

```bash
# Fetch bookmarks (local)
curl "http://localhost:8080?limit=10&fetch_text=true"

# Fetch bookmarks (deployed)
curl "https://your-cloud-run-url?limit=10&fetch_text=true"
```

### Parameters
- `limit`: Number of bookmarks to fetch (default: 25)
- `fetch_text`: Whether to fetch article text (default: false)

## Architecture

This service uses:
- **Ray.Di**: Lightweight dependency injection
- **Koriym.EnvJson**: Environment configuration with JSON schema validation
- **Google Cloud Functions Framework**: Cloud Run Functions runtime
- **OAuth 1.0a**: Instapaper API authentication
- **Google Cloud Storage**: Output storage
- **Monolog**: PSR-3 compliant logging

## Development Commands

```text
start             Start local Cloud Functions server
test              Run unit tests
coverage          Generate test coverage report
phpdbg            Generate test coverage report (phpdbg)
pcov              Generate test coverage report (pcov)
cs                Check the coding style
cs-fix            Fix the coding style
phpstan           Analyze code for errors using PHPStan
psalm             Analyze code for type safety using Psalm
phpmd             Analyze PHP code for potential issues
baseline          Generate baseline for PHPStan and Psalm
crc               Run composer require checker
metrics           Build metrics report
clean             Remove temporary files
sa                Run static analysis
tests             Run tests and quality checks
build             Build project
```

## License

MIT License