# Welcome to the vZJX ARTCC Website Repository

## Overview

This repository contains the application source code, configuration, tests, and supporting documentation for the project.

The project is developed through reviewed changes, automated checks, and documented contribution and security practices.

## Getting Started

To set up the project locally, clone the repository and install the required dependencies.

```bash
git clone git@github.com:zjx-artcc/site-laravel.git
```

Follow the setup instructions used by your team for environment configuration, dependency installation, database setup, and local development.

Common setup tasks may include:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Update your local `.env` file as needed for your development environment. Please see [`CONTRIBUTING.md`](CONTRIBUTING.md) for more details.

## Testing

Run the test suite before opening a pull request:

```bash
php artisan test
```

## Contributing

Contributions should follow the project’s contribution guidelines.

See [`CONTRIBUTING.md`](CONTRIBUTING.md) for details on local setup expectations, branching, pull requests, coding standards, testing, and review process.

## Security

Please do not report security vulnerabilities through public issues.

See [`SECURITY.md`](SECURITY.md) for supported versions, vulnerability reporting instructions, disclosure expectations, and security practices.

## License

This project is licensed under GPLv3. See the project license file for more information.
