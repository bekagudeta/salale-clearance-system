# Salale Clearance System

A Laravel-based Clearance Management System that streamlines student clearance workflows across departments. The application provides request submission, multi-stage approvals, PDF generation, email notifications, scheduled reminders, and a lightweight API for integrations.

## Key Features

- Centralized clearance request submission and tracking
- Departmental approvals with flexible approval rules
- Role-based access control using `spatie/laravel-permission`
- Automatic PDF generation for completed clearances (`barryvdh/laravel-dompdf`)
- Asynchronous jobs and notifications (email, queued jobs)
- QR-code support for clearance verification (`simplesoftwareio/simple-qrcode`)
- RESTful API endpoints for integration with other systems
- Activity logging and notification records

## Technology Stack

- Framework: Laravel (see `composer.json`) — this project targets PHP ^8.2 and Laravel ^12
- Queue: Laravel queues (database/Redis) for background jobs
- Database: MySQL / MariaDB (or SQLite for local testing)
- PDF: barryvdh/laravel-dompdf
- Permissions: spatie/laravel-permission
- QR Codes: simplesoftwareio/simple-qrcode

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm (for frontend assets)
- A supported database (MySQL, MariaDB, or SQLite)
- Optional: Redis for queue/ cache

## Quick Start

1. Clone the repository:

```bash
git clone https://github.com/bekagudeta/salale-clearance-system.git
cd salale-clearance-system
```

2. Install PHP dependencies and build assets:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Configure your `.env` database and mail settings, then run migrations and seeders:

```bash
php artisan migrate --seed
php artisan storage:link
```

4. Install and build frontend assets:

```bash
npm install
npm run build   # or `npm run dev` for local development
```

5. Start the application and queue workers (development):

```bash
php artisan serve
php artisan queue:work --tries=3
```

Tip: A convenient project setup command is defined in `composer.json` as `composer run setup`.

## Configuration & Common Tasks

- Environment: update `.env` for DB, mail, and queue driver
- Cron / Scheduler: add the Laravel scheduler to your server's cron:

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

- Queues: use `php artisan queue:work` (or a supervisor) for background jobs

## API

API routes are defined under `routes/api.php`. Use API tokens or the configured auth guards to interact with endpoints. See the `app/Http/Controllers/Api` folder for available controllers and request contracts.

## Testing

Run the test suite with:

```bash
composer test
```

Or run PHPUnit directly:

```bash
php artisan test
```

## Contributing

Contributions, bug reports, and feature requests are welcome. Please open issues or submit pull requests and follow the repository's contribution guidelines.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

## Maintainers / Support

For questions or support, open an issue in this repository. Include environment details and reproduction steps.

