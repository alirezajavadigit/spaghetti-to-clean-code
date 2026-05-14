# Spaghetti to Clean Code

[![CI](https://github.com/alirezajavadigit/spaghetti-to-clean-code/actions/workflows/ci.yml/badge.svg)](https://github.com/alirezajavadigit/spaghetti-to-clean-code/actions/workflows/ci.yml)

A Laravel refactoring project. Started as a working but unmaintainable order management system, ended up as something I'd actually want to work with long-term.

---

## What this is

An Order Management System built with Laravel 13 and PHP 8.3. It handles customers, products, orders, and reporting. Nothing groundbreaking in terms of features — the point of the project is the architecture and the journey from the first version to the current one.

The original codebase worked. You could create orders, manage products, run reports. But the way it was written made every small change feel risky, and adding anything new meant reading through hundreds of lines of tangled controller logic first.

---

## Why I built it

The spaghetti code in this project was generated intentionally by Claude AI — I asked for a working but badly written Laravel app, and then treated it as a real refactoring task.

The goal was to document what a proper refactor actually looks like, step by step, with real commits and real architectural decisions. Not a tutorial with a made-up before/after, but an actual codebase where you can trace every change and understand why it was made.

This is the first project in a series. The next ones will cover modular monolith architecture and eventually microservices — same idea, increasing complexity. Everything will be on my GitHub as it's done.

---

## Tech stack

- Laravel 13 / PHP 8.3
- MySQL
- Blade + Bootstrap 5
- Repository + Service + DTO pattern
- PHPUnit / Pest for testing
- GitHub Actions for CI

---

## Architecture

```
app/
├── DTOs/               # typed input objects per feature
├── Http/
│   ├── Controllers/    # just routing and response, nothing else
│   └── Requests/       # validation lives here
├── Models/             # casts, relationships, accessors
├── Repositories/
│   ├── Contracts/      # interfaces
│   └── Eloquent/       # implementations
└── Services/           # all business logic
```

---

## The refactor — what actually changed and why it mattered

This is the part worth reading if you want to understand the engineering decisions.

### The original state

The first version had controllers that did everything. A single `store` method in `OrderController` was over 60 lines: it validated input manually with `if (!$request->name)`, ran raw SQL queries with user input concatenated directly into the string, calculated tax, deducted stock, created order items, handled file uploads, and returned a redirect — all in sequence, all in one place.

`ProductController` had the same SQL injection problem. The search was built like this:

```php
$products = DB::select("SELECT * FROM products WHERE name LIKE '%$q%' OR sku LIKE '%$q%'");
```

Passwords were stored with `md5()`. Not `bcrypt`, not `Hash::make()` — `md5`. Every controller method started with `if (!Auth::check()) return redirect('/login')` which was copy-pasted everywhere.

The `ReportController` loaded every single order with `Order::all()` and then looped through them in PHP to count statuses and sum revenue. On a large dataset this would just fall over.

It wasn't broken. It passed requests and returned responses. But it was the kind of code where you fix one thing and break two others, where you can't test anything in isolation, and where a new developer would need a week just to understand what the order creation flow actually does.

### What was hard to maintain

**No separation of concerns.** Business logic, data access, and HTTP handling were all mixed together. Changing how orders calculated tax meant finding every place that referenced it — which was just buried in a controller method with no clear boundaries.

**Hardcoded status values everywhere.** `$order->status == 1` appeared in controllers, views, and raw SQL. When I wanted to add a label or color for a status, I had to track down every instance manually.

**Raw queries with direct string interpolation.** The search functionality in both orders and products was a SQL injection waiting to happen. These weren't hypothetical — they were right there in production-facing code.

**Delete via GET requests.** Routes like `GET /orders/{id}/delete` meant anyone with the URL could trigger a deletion. No CSRF protection, no proper HTTP verb, nothing.

**Validation scattered everywhere.** Some methods had manual `if (!$request->field)` checks, some had nothing at all. There was no consistent place to look for what a request was supposed to contain.

### How the architecture was improved

**Repository pattern with interfaces.** Every model now has a repository interface and an Eloquent implementation. Controllers and services never touch the model directly. This makes the data layer swappable and testable independently.

**Service layer for business logic.** `OrderService` handles the full order creation flow: checking for pending orders, resolving line items, deducting stock, calculating tax, and creating order items — all wrapped in a database transaction. The controller just calls `$this->orderService->store($dto)` and handles the redirect.

**DTOs for typed input.** Instead of passing raw `Request` objects into services, every write operation has a DTO that accepts the base `Illuminate\Http\Request`. `StoreOrderDTO::fromRequest($request)` gives you a typed, readonly value object. The service doesn't know or care where the data came from — and the DTOs are fully testable without mocking FormRequest classes.

**Proper authorization.** Replaced every manual `Auth::check()` with `Route::middleware('auth')`. Admin-only routes use a Gate defined in `AppServiceProvider`. Views use `@can('admin')` instead of `Auth::user()->role == 'admin'` inline everywhere.

**Model casts instead of manual transformation.** The `User` model now has `'password' => 'hashed'` as a cast. `Order` has status constants and accessors for `status_label` and `status_color`, which eliminates the repeated if/elseif chains that were in almost every view.

**Performance fix in reporting.** The dashboard and report controllers no longer load full collections into memory. A single `selectRaw` with `CASE WHEN` handles all the aggregations in one database round trip.

### What the new structure gives you

The codebase is now layered in a way where you can change how orders are stored without touching the service, change business rules without touching the controller, and add new request types without duplicating validation logic. Each class has one reason to exist.

It's not over-engineered. There are no event buses, no CQRS, no microservices. It's a monolith, and the architecture reflects that — but a monolith with clear internal boundaries that could realistically be maintained by a team.

---

## Testing

The project has full feature and unit test coverage written after the refactor was complete.

**Feature tests** cover the full HTTP layer — authentication, authorization, CRUD operations, business rule enforcement (stock checks, pending order checks), and validation. They run against a real MySQL database using `RefreshDatabase`.

**Unit tests** cover services, model accessors, and DTOs in isolation — repositories are mocked with Mockery so tests don't touch the database at all.

```
tests/
├── Feature/
│   ├── Auth/         # login, logout, guest redirects
│   ├── Customer/     # CRUD, authorization, order display
│   ├── Dashboard/    # view data, guest redirect
│   ├── Order/        # creation, stock, status, search, authorization
│   ├── Product/      # CRUD, authorization, order constraints
│   └── Report/       # admin-only access, CSV export
└── Unit/
    ├── DTOs/         # fromRequest mapping
    ├── Models/       # accessors, constants
    └── Services/     # business logic with mocked repositories
```

Run the tests:

```bash
php artisan test
```

---

## CI/CD

Every push to `develop` or `main` runs the full test suite automatically via GitHub Actions. The pipeline spins up a MySQL 8.0 service container, installs dependencies, runs migrations, and executes all tests.

---

## Setup

```bash
git clone https://github.com/alirezajavadigit/spaghetti-to-clean-code.git
cd spaghetti-to-clean-code

composer install

cp .env.example .env
php artisan key:generate

# update .env with your database credentials, then:
php artisan migrate --seed

php artisan serve
```

Default accounts after seeding:

| Email | Password | Role |
|-------|----------|------|
| admin@example.com | password123 | admin |
| staff@example.com | password123 | staff |

---

## Branch structure

The entire refactor is committed in logical, reviewable branches following Git Flow:

| Branch | What it contains |
|--------|-----------------|
| `feature/auth-repository-pattern` | Auth refactor with repository, service, DTO |
| `feature/order-product-refactor` | Orders and products, full architecture |
| `feature/customer-report-refactor` | Customers, reports, routing overhaul |
| `feature/dashboard-refactor` | Dashboard service layer |
| `refactor/views` | All Blade views cleaned up |
| `refactor/database-seeders` | Seeders split and cleaned up |
| `fix/order-controller-bugs` | Bug fixes discovered during refactoring |
| `feature/feature-tests` | Factories and full feature test suite |
| `feature/unit-tests` | Unit tests for services, models, DTOs |
| `fix/failing-feature-tests` | Fixes found while writing feature tests |
| `fix/failing-unit-tests` | DTO base request refactor, auth service fix |
| `chore/github-ci` | GitHub Actions CI pipeline |
| `chore/docker` | Dockerfile for local development |

If you want to see the before state, the original code is on the initial commits before any feature branches were merged.

---

## What I learned

The code worked from day one. That was almost the problem — when something works, it's easy to keep adding to it without stopping to ask if the structure still makes sense.

Maintenance started getting painful fast. Duplicated logic in multiple controllers, unclear boundaries between what each class was responsible for, and components doing four things when they should have been doing one. Every new feature required reading through existing code that wasn't written to be read.

During the refactor I stopped thinking about features and started thinking about architecture. Who owns this logic? Where does this data transformation belong? What happens when this changes six months from now? Those questions drove most of the decisions more than any pattern or principle.

Writing tests after the refactor also revealed things the refactor itself missed — a missing admin check in a controller, a route conflict that only showed up under test conditions, DTO classes tightly coupled to FormRequest types that made them untestable in isolation. The tests weren't just verification, they were a second round of design feedback.

The biggest thing I took away: clean code isn't about aesthetics. It's about reducing the cost of future changes. A well-structured codebase doesn't mean fewer bugs on day one — it means the bug on day 180 takes an hour to fix instead of a day.

---

## Contact

Alireza Javadi — [github.com/alirezajavadigit](https://github.com/alirezajavadigit)