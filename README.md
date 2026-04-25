# Meow List 🐾

Meow List is a playful, cat-themed To-Do application designed for efficiency and feline charm. It allows users to manage their tasks with a touch of "meow-gic," featuring smooth animations and a robust backend.

## Technology Stack

- **Backend**: PHP 8.4+, Symfony 7.4 (LTS)
- **Database**: Doctrine ORM (MySQL/PostgreSQL)
- **Frontend**: Twig, Native JavaScript, Symfony UX Turbo & Stimulus
- **Architecture**: Domain-Driven Design (DDD) with four explicit layers:
  - **UI**: Controllers, Twig templates, and Form types.
  - **Application**: Use cases and orchestration logic.
  - **Domain**: Core business logic, Entities, and Value Objects (framework-agnostic).
  - **Infrastructure**: Persistence implementations and external service adapters.

## Architecture & Guidelines

This project strictly adheres to Domain-Driven Design principles. The Domain layer is the heart of the system and has zero dependencies on external frameworks or infrastructure. For a detailed breakdown of our coding standards and architectural rules, please refer to [AGENTS.md](AGENTS.md).

## Getting Started

### Prerequisites

- **For Local Development**:
  - PHP 8.4 or higher
  - Composer
  - Symfony CLI
  - A running database (MySQL/PostgreSQL) or Docker for database services
- **For Docker Development**:
  - Docker and Docker Compose

---

### Option 1: Local Development

1. **Clone the repository:**
   ```bash
   git clone https://github.com/djanicki/meow-list.git
   cd meow-list
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   Copy `.env` to `.env.local` and adjust your `DATABASE_URL`.
   ```bash
   cp .env .env.local
   ```

4. **Database Setup:**
   If you have Docker installed, you can easily start a database:
   ```bash
   docker compose up -d database
   ```
   Then run migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. **Start the server:**
   ```bash
   symfony serve
   ```
   The app will be available at `https://localhost:8000`.

---

### Option 2: Docker Development

This method runs the entire stack (PHP-FPM, Nginx, Postgres) in containers.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/djanicki/meow-list.git
   cd meow-list
   ```

2. **Start the containers:**
   ```bash
   docker compose up -d
   ```

3. **Install dependencies & Setup Database:**
   ```bash
   docker compose exec php composer install
   docker compose exec php bin/console doctrine:migrations:migrate
   ```

4. **Access the application:**
   Open [http://localhost](http://localhost) in your browser.

## Testing

We use PHPUnit for testing. All new features are required to have unit and integration tests.

**Local:**
```bash
php bin/phpunit
```

**Docker:**
```bash
docker compose exec php bin/phpunit
```

---
*Created with 🐈 by the Meow List team.*
