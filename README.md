# Student Management System

A Laravel application for managing students and the courses they're enrolled in. It has searchable, paginated student records, course management with enrolment counts, server-side validation, and a feature-test suite that runs in CI on every push.

![PHP](https://img.shields.io/badge/PHP_8.2--8.4-777BB4?logo=php&logoColor=fff)
![Laravel](https://img.shields.io/badge/Laravel_11-FF2D20?logo=laravel&logoColor=fff)
![SQLite / MySQL](https://img.shields.io/badge/SQLite_%2F_MySQL-003B57?logo=sqlite&logoColor=fff)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?logo=bootstrap&logoColor=fff)
[![Tests](https://github.com/OHURU-IAN/student-management-system/actions/workflows/tests.yml/badge.svg)](https://github.com/OHURU-IAN/student-management-system/actions/workflows/tests.yml)

![Students list](docs/screenshots/students.png)

## Features

- **Students:** create, view, edit and delete student records (name, email, address, mobile, course).
- **Search and filter:** search by name, email or mobile, filter by course, and page through results 10 at a time. The query string is kept across pages.
- **Courses:** create, view, edit and delete courses. The list shows how many students are enrolled, and each course page lists its students.
- **Relationships:** `Student belongsTo Course` and `Course hasMany Student`. Deleting a course leaves its students on record as *unassigned* (the foreign key uses `nullOnDelete`) instead of deleting them.
- **Validation:** Form Request classes check required fields, email format and uniqueness (ignoring the current record on update), phone number format, and that the chosen course exists. Course codes are normalised to upper case, so uniqueness ignores case.
- **Dashboard:** totals for students, courses and unassigned students, plus the most recently added students.

| Dashboard | Course detail |
| --- | --- |
| ![Dashboard](docs/screenshots/dashboard.png) | ![Course](docs/screenshots/course.png) |

## Architecture

```
app/
├── Http/Controllers/
│   ├── DashboardController.php   Single-action controller for /
│   ├── StudentController.php     Resource controller (7 REST actions)
│   └── CourseController.php      Resource controller (7 REST actions)
├── Http/Requests/
│   ├── StudentRequest.php        Validation for create and update
│   └── CourseRequest.php         Validation plus code normalisation
└── Models/
    ├── Student.php               belongsTo Course, search() query scope
    └── Course.php                hasMany Student
database/
├── migrations/                   students, courses, and email + course_id on students
├── factories/                    Student and Course factories
└── seeders/DatabaseSeeder.php    4 courses and 35 students of sample data
resources/views/
├── layout.blade.php              Bootstrap 5 shell, navigation and flash messages
├── dashboard.blade.php
├── students/                     index, show, create, edit, shared _form
├── courses/                      index, show, create, edit, shared _form
└── partials/delete-button.blade.php
tests/Feature/                    StudentTest, CourseTest
```

Routes (`routes/web.php`):

```php
Route::get('/', DashboardController::class)->name('dashboard');
Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
```

## Getting started

Requirements: PHP 8.2+ (with `pdo_sqlite`) and Composer.

```bash
composer install
cp .env.example .env              # SQLite by default
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed        # sample courses and students
php artisan serve                 # http://localhost:8000
```

To use MySQL instead, set `DB_CONNECTION=mysql` and the `DB_*` credentials in `.env`, then run `php artisan migrate --seed`.

## Testing

```bash
php artisan test          # 20 feature tests on an in-memory SQLite database
vendor/bin/pint --test    # code style (Laravel preset)
```

The tests cover every resource action, search, course filtering, pagination, validation (including unique email and course code on create and update), 404 handling, and keeping students when their course is deleted.
GitHub Actions runs both commands on PHP 8.2, 8.3 and 8.4 for every push and pull request.

## Possible extensions

- Authentication for staff users (Laravel Breeze)
- Many-to-many enrolments with grades per course
- CSV import and export of student records
