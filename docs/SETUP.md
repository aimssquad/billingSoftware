# Project Setup Guide

Use this guide when you clone the repository to get the Billing SaaS API running on your machine.

**Repository:** https://github.com/aimssquad/billingSoftware

---

## 1. Prerequisites

Install these before starting:

| Requirement | Version / notes |
|-------------|------------------|
| **PHP** | 8.2 or higher (with extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml) |
| **Composer** | [getcomposer.org](https://getcomposer.org/) |
| **Database** | **MySQL 8** (recommended) or **MariaDB** – or **SQLite** for quick local dev |
| **Git** | To clone the repository |

Check versions:

```bash
php -v
composer -v
mysql --version
```

---

## 2. Clone the repository

```bash
git clone https://github.com/aimssquad/billingSoftware.git
cd billingSoftware
```

Repository: **https://github.com/aimssquad/billingSoftware**

---

## 3. Install PHP dependencies

```bash
composer install
```

This installs Laravel, Sanctum, and other dependencies. Use `composer install --no-dev` on production if you don’t need dev tools.

---

## 4. Environment configuration

### 4.1 Copy environment file and generate key

```bash
cp .env.example .env
php artisan key:generate
```

### 4.2 Configure database

**Option A – MySQL (recommended for real use)**

Edit `.env` and set:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=billing_software
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database if it doesn’t exist:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS billing_software CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Option B – SQLite (quick local test)**

Keep in `.env`:

```env
DB_CONNECTION=sqlite
```

Create the SQLite file (if it doesn’t exist):

```bash
# Windows (PowerShell)
New-Item -Path database\database.sqlite -ItemType File -Force

# Linux / macOS
touch database/database.sqlite
```

Comment or remove `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` when using SQLite.

### 4.3 Application URL (optional)

Set the URL where the API will run (used in emails and links):

```env
APP_URL=http://localhost:8000
```

For production, use your real domain, e.g. `https://api.yourdomain.com`.

### 4.4 Mail (optional – for forgot password & registration emails)

To send real emails, configure SMTP in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=465
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

If you leave `MAIL_MAILER=log`, emails are written to `storage/logs/laravel.log` instead of being sent.

### 4.5 Frontend URLs (optional)

If you have a separate frontend app:

```env
# Link in “forgot password” email
FRONTEND_PASSWORD_RESET_URL=https://yourapp.com/reset-password

# “Go to” link in registration success email
APP_FRONTEND_URL=https://yourapp.com
```

---

## 5. Database setup

Run migrations and seed data (roles, plans, super admin, demo org):

```bash
php artisan migrate
php artisan db:seed
```

You should see migrations and seeders run without errors.

---

## 6. Run the application

Start the development server:

```bash
php artisan serve
```

You should see something like:

```
INFO  Server running on [http://127.0.0.1:8000]
```

- **API base URL:** `http://localhost:8000/api` (or `http://127.0.0.1:8000/api`)

---

## 7. Verify with Postman (or any HTTP client)

1. **Import collection and environment** from the `postman/` folder:
   - `postman/Billing-SaaS-API.postman_collection.json`
   - `postman/Billing-SaaS-Local.postman_environment.json`

2. **Select environment:** In Postman, choose **Billing SaaS - Local**. Set **base_url** to `http://localhost:8000/api` if different.

3. **Login:** Run **Auth → Login (Org Owner - Demo)**. The token is saved automatically.

4. **Call an endpoint:** Run **Org Scoped → Org Me** or **Organization Profile**. You should get JSON with user and organization data.

### Default test accounts (after seeding)

| Role | Email | Password |
|------|--------|----------|
| Super Admin | superadmin@billing.test | password |
| Org Owner (demo) | owner@demo.com | password |

---

## 8. Optional: queue worker (for emails)

If you use queues for sending mail, run a worker in a separate terminal:

```bash
php artisan queue:work
```

Make sure `.env` has `QUEUE_CONNECTION=database` (or `redis`). For simple setups, mail can be sent synchronously without a queue.

---

## 9. Optional: scheduler (cron)

For future cron jobs (e.g. subscription expiry checks), add to your crontab:

```bash
* * * * * cd /path/to/billing-software && php artisan schedule:run >> /dev/null 2>&1
```

Replace `/path/to/billing-software` with the actual project path.

---

## 10. Troubleshooting

| Issue | What to do |
|-------|------------|
| **500 error** | Check `storage/logs/laravel.log`. Ensure `storage/` and `bootstrap/cache/` are writable. Run `php artisan config:clear` and `php artisan cache:clear`. |
| **Database connection error** | Verify `.env` DB_* values. For MySQL, confirm the database exists and the user has permissions. |
| **401 on protected routes** | Send header `Authorization: Bearer {token}`. Get token from `POST /api/login`. |
| **403 on /api/super/** | Only users with role `super_admin` can access. Use super admin login. |
| **Org routes return 400** | If logged in as super_admin, send header `X-Organization-Id: {organization_id}`. |

---

## 11. Next steps

| Document | Purpose |
|----------|---------|
| [PROJECT-SCOPE.md](PROJECT-SCOPE.md) | Understand project scope, phases, roles, multi-tenancy, and architecture. |
| [API.md](API.md) | Full API reference for every route (request/response, errors). |
| [../README.md](../README.md) | Quick start and links. |
| [../postman/README.md](../postman/README.md) | Postman import and usage. |

---

## Quick copy-paste summary

```bash
git clone https://github.com/aimssquad/billingSoftware.git
cd billingSoftware
composer install
cp .env.example .env
php artisan key:generate
# Edit .env: set DB_* for MySQL (or keep SQLite)
php artisan migrate
php artisan db:seed
php artisan serve
# API: http://localhost:8000/api
```

After this, use Postman with the collection and **Login (Org Owner - Demo)** to test the API.
