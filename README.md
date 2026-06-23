# Mini Issue Tracker

A small Laravel application where a team can manage projects, issues, tags, and comments.

## Features

- Projects: Full CRUD. View a project with its issues.
- Issues: Full CRUD with filters by status, priority, and tag.
- Tags: Attach and detach tags via AJAX (no page reload).
- Comments: Load comments via AJAX (paginated) and add new ones with validation.

## Tech Stack

- Laravel 13
- PHP 8.4
- SQLite
- Blade + Tailwind CSS
- Vanilla JavaScript (Fetch API)

## Installation

1. git clone https://github.com/kleakurhasku/mini-issue-tracker.git
2. cd mini-issue-tracker
3. composer install
4. cp .env.example .env
5. php artisan key:generate
6. php artisan migrate:fresh --seed
7. php artisan serve

Then open http://127.0.0.1:8000
