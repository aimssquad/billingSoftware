# Billing SaaS API (Laravel 11)

Multi-tenant billing backend — **API only**, no UI. Use from mobile apps, React, Vue, or any frontend.

**Repository:** https://github.com/aimssquad/billingSoftware

**New to the project?**  
- **Setup (clone, install, run):** [docs/SETUP.md](docs/SETUP.md)  
- **Understand the system:** [docs/PROJECT-SCOPE.md](docs/PROJECT-SCOPE.md)

## Quick start

```bash
cp .env.example .env && php artisan key:generate
# Set DB_CONNECTION=mysql and DB_* in .env if using MySQL
php artisan migrate
php artisan db:seed
php artisan serve
```

- **Base URL:** `http://localhost:8000/api`
- **Auth:** Bearer token (Sanctum). Login: `POST /api/login` with `email` and `password`.

### Postman

1. Import the collection and environment from the `postman/` folder.
2. Use **Billing SaaS - Local** environment.
3. **Login (Super Admin):** `superadmin@billing.test` / `password`
4. **Login (Org Owner):** `owner@demo.com` / `password`
5. Copy the `token` from the login response into the environment variable `token`.

See `postman/README.md` for details.

## Roles & routes

| Role         | Description        | Example routes                          |
|-------------|--------------------|-----------------------------------------|
| super_admin | Platform owner     | `GET /api/super/plans`, `GET /api/super/organizations` |
| org_owner / org_user | Company user | `GET /api/org/me`, `GET /api/org/usage`, `POST /api/org/invoices` |

Org-scoped routes require the authenticated user to belong to an organization (or super admin must send `X-Organization-Id` header).

## Main endpoints

- `POST /api/login` — Login (returns token)
- `POST /api/organizations/register` — Register new organization + owner
- `GET /api/org/usage` — Current month usage & invoice limit
- `POST /api/org/invoices` — Create invoice (enforces plan limit)

## Developer documentation

| Doc | Purpose |
|-----|---------|
| **[docs/SETUP.md](docs/SETUP.md)** | **Project setup** – clone, install, .env, database, run, Postman. Start here when you clone the repo. |
| **[docs/PROJECT-SCOPE.md](docs/PROJECT-SCOPE.md)** | **Project scope & developer guide** – what we build, phases, roles, multi-tenancy, database, business rules, architecture. |
| **[docs/API.md](docs/API.md)** | **API reference** – every route with method, path, request/response bodies, errors, and examples. |

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
