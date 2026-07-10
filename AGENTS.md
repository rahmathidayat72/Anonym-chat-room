# EphemChat — Agent Instructions

## Project

EphemChat: temporary anonymous chat room. Laravel 13, PHP ^8.3, Tailwind v4, Vite, vanilla JS + Axios.

## Must-read before coding

- `agents/prd.md` — full product spec
- `agents/task-instruction.md` — ordered task checklist (update progress as you go)

## Source root

The Laravel project lives in `chat_room/`. All commands run from there.

## Commands

| Action | Command |
|--------|---------|
| Full setup | `composer setup` |
| Dev server | `composer dev` (concurrently: serve + queue + logs + vite) |
| Run tests | `composer test` (`config:clear` then `artisan test`) |
| Lint | `./vendor/bin/pint` |
| DB default | SQLite (`.env.example`); PRD targets MySQL — change `DB_CONNECTION=mysql` |

## Architecture notes

- **No auth.** Anonymous sessions only. Laravel session stores username.
- **Custom `User` model** needed per PRD (separate from the default auth `User.php` which is for Laravel auth).
- **`Room`, `Message`, `User`** (chat) models with `ON DELETE CASCADE` foreign keys.
- **AJAX polling** every 2s (Axios), not WebSockets.
- **Rate limit:** 1 msg/s/session.
- **XSS protection:** `mews/purifier` (install via `composer require mews/purifier`).
- **Room cleanup:** `php artisan rooms:cleanup` via scheduler every minute (`routes/console.php`).
- **Tailwind v4:** CSS-based config in `resources/css/app.css`, no `tailwind.config.js`.
- **Indent:** 4 spaces (PHP/JS), 2 spaces (YAML) — per `.editorconfig`.
- **Views under `resources/views/`** using Blade.
