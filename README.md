# News Aggregator Backend

## Getting Started

### Prerequisites

-   [Docker](https://www.docker.com/) & Docker Compose
-   [PHP](https://www.php.net/) 8.2+ (for local development)
-   [Composer](https://getcomposer.org/)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/mohamed-samir907/innoscripta-challenge
    cd news-aggregator
    ```

2.  **Environment Setup:**
    ```bash
    cp .env.example .env
    ```
    Update `.env` with your API keys for NewsAPI, The Guardian, and NYTimes.

3.  **Install Dependencies:**
    ```bash
    composer install
    ```

4.  **Start Application (Docker):**
    ```bash
    make up
    ```
    This will start the following containers:
    -   `news_app`: Laravel application (Port 8000)
    -   `news_mysql`: Main database (Port 3306)
    -   `news_mysql_test`: Test database (Port 3307)
    -   `news_redis`: Redis for caching/queues

5.  **Run Migrations:**
    ```bash
    make migrate
    ```

The API will be available at `http://localhost:8000/api`.

## Architecture

This project follows a modular, service-oriented architecture to ensure maintainability and scalability.

### Service Layer (`app/Services`)
Business logic is encapsulated in dedicated services:
-   **`ArticleService`**: Handles article retrieval and filtering. It uses the **Pipeline Pattern** to apply filters dynamically.
-   **`UserPreferenceService`**: Manages user preferences (sources, categories, authors).
-   **`FeedService`**: Generates a personalized news feed by combining user preferences with the article service.
-   **`NewsAggregatorService`**: Orchestrates the fetching of articles from external sources and storing them in the database.

### Pipelines (`app/Pipelines/Articles`)
Article filtering is implemented using a pipeline of filter classes:
-   `ByKeyword`: Uses MySQL **Full-Text Search** for efficient querying.
-   `ByDate`: Filters by date range.
-   `ByCategory`: Filters by category.
-   `BySource`: Filters by news source.
-   `ByAuthor`: Filters by author.

### Data Fetching
The application aggregates news from three major sources: **NewsAPI**, **The Guardian**, and **New York Times**.
-   **Command**: `php artisan news:fetch` (or `make fetch`) triggers the fetching process.
-   **Job**: `FetchArticlesFromSource` is dispatched for each source to handle API requests asynchronously and store data.
-   **Factory**: `NewsSourceFactory` instantiates the correct source implementation (`NewsAPI`, `TheGuardian`, `NYTimes`) based on the source name.

## Testing

The project uses **PestPHP** for testing and runs tests against a dedicated MySQL container to support Full-Text search features.

### Running Tests
To run the full test suite inside the Docker container:

```bash
make test
```

### Test Suite
-   **Feature Tests** (`tests/Feature`): Cover API endpoints, controller logic, and database interactions.
-   **Unit Tests** (`tests/Unit`): Verify specific logic, such as API response mapping in source implementations.

## API Documentation

Comprehensive API documentation is available in **OpenAPI v3.0 (Swagger)** format.

-   **File**: [`swagger.json`](./swagger.json)
-   **Endpoints Documented**:
    -   `POST /register`: Register a new user.
    -   `POST /login`: Login and get access token.
    -   `POST /logout`: Logout and revoke token.
    -   `GET /articles`: List and filter articles.
    -   `GET /articles/{id}`: Get article details.
    -   `GET /feed`: Get personalized feed.
    -   `GET /user/preferences`: Get user preferences.
    -   `POST /user/preferences`: Update user preferences.

## Commands

| Command | Description |
| :--- | :--- |
| `make up` | Start Docker containers |
| `make down` | Stop Docker containers |
| `make test` | Run tests in Docker |
| `make migrate` | Run database migrations |
| `php artisan news:fetch` | Fetch articles from all sources |
| `php artisan news:fetch {source}` | Fetch articles from a specific source |
