# Kobo Commerce: B2B ordering and invoicing API

A Laravel 13 modular monolith on PHP 8.3+, with Pest for tests and SQLite as the local and test database.

## Layout

- `app/Modules/{Customers,Catalog,Orders,Billing}`: one folder per module (Models, Contracts, Internal, Http, Providers, Actions).
- `app/Support`: shared Money and Tenancy code.
- `routes/api.php`: the API routes. Every API request carries an `X-Tenant` header.

## Working here

- Run the tests with `php vendor/bin/pest`. The suite must pass when you finish.
- Dependencies are already installed. Don't run Composer or touch `vendor/`. There is no network access.
- Make database changes through migrations in `database/migrations`.
