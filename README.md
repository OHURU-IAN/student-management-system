# Student Management System

A Laravel application for managing student records (name, address, mobile number) with a Bootstrap admin layout.

![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?logo=php&logoColor=fff)
![Laravel](https://img.shields.io/badge/Laravel_11-FF2D20?logo=laravel&logoColor=fff)
![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=fff)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?logo=bootstrap&logoColor=fff)

> **Status: early development.** The database schema and base layout are in place. The CRUD controller and views are next.

## Current state

| Piece | Location | Status |
| --- | --- | --- |
| `students` table (id, name, address, mobile, timestamps) | `database/migrations/2024_05_15_111326_create_students_table.php` | Done |
| Admin layout with sidebar navigation (Bootstrap 5) | `resources/views/layout.blade.php` | Done |
| View stubs for index, create, edit, show | `resources/views/student/` | Stubbed |
| `Student` model and `StudentController` (resource) | `app/` | To do |
| `Route::resource('students', …)` | `routes/web.php` | To do |

## Planned design

- **Routing:** `Route::resource('students', StudentController::class)`, giving the standard seven REST actions
- **Validation:** Form Request classes for create and update
- **Views:** Blade templates that extend `layout.blade.php`, with pagination on the index page
- **Tests:** feature tests for each resource action, using PHPUnit and `RefreshDatabase`

## Getting started

Requirements: PHP 8.2 or 8.3 and Composer. The current `composer.lock` predates PHP 8.4 support; run `composer update` to use PHP 8.4.

```bash
composer install
cp .env.example .env        # SQLite by default
touch database/database.sqlite
php artisan key:generate
php artisan migrate
php artisan serve           # http://localhost:8000
```

## Roadmap

1. Student model, resource controller and routes
2. Blade CRUD views with validation errors and flash messages
3. Search and pagination
4. Feature tests and a GitHub Actions workflow running `php artisan test`
