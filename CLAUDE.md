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

## Team memory

Decisions about this codebase, and why they were made, live in `.agent/memory/`.

@.agent/memory/INDEX.md

- Before changing files, run `php tools/memory for <path>` for the files you'll touch, and follow the live records.
- Before you finish a task, check whether it produced something a future session would need that the code doesn't show: a reason the developer gave, a conflict between the ticket and a record that you resolved, a record you found stale or wrong, or an approach you rejected and why. If so, end with one line naming it and offer to propose it with the `remember` skill. Don't propose without a yes.
- Never accept or reject records yourself, and never edit `decisions.jsonl` by hand.
