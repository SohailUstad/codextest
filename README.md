# Orderly

A simple Laravel 13 order management service with a web dashboard, JSON API and MySQL 8.4 storage.

## Run

Requires PHP 8.3+ with pdo_mysql, mbstring, XML, Composer and Docker Compose.

```sh
composer install
cp .env.example .env
php artisan key:generate
# Set unique DB_PASSWORD and MYSQL_ROOT_PASSWORD values in .env first.
docker compose up -d --wait
php artisan migrate --seed
php artisan serve --host=0.0.0.0 --port=8090
```

This workspace includes PHP at `/workspace/work/bin/php` and Composer at `/workspace/work/bin/composer.phar`.

## API

- GET /api/orders — paginated list, optional search and status filters
- GET /api/orders/{id} — details
- POST /api/orders — create
- PATCH /api/orders/{id} — update status
- DELETE /api/orders/{id} — delete
- GET /api/orders-summary — totals

Create example:

```json
{"customer_name":"Alex Smith","customer_email":"alex@example.com","items":[{"name":"Keyboard","quantity":2,"unit_price":"49.95"}],"notes":"Sample order"}
```

Update example: `{"status":"processing"}`. Statuses: pending, processing, shipped, completed, cancelled.

Amounts are USD; the server computes totals in integer cents. Invalid input returns 422, missing records 404, creation 201, deletion 204.

## Demo and verification

This is a shared demo without authentication. Use sample data only. Temporary links last while the environment and tunnel remain running, with no guaranteed lifetime.

Verified local dashboard and creation in Chromium. HTTP checks cover creation, cent calculations, status changes, deletion and validation. Public HTTPS dashboard and API verified with curl. Public browser QA was limited by the cloud browser's proxy certificate trust. Default Laravel test suite passes (2 tests).

MySQL listens on localhost:3306 and uses the persistent Docker volume `order-service_mysql_data`. Laravel connects with a dedicated `order_app` user. The original four SQLite orders were migrated with IDs, items, totals and timestamps preserved. The SQLite file remains as a backup; the running app uses MySQL.
