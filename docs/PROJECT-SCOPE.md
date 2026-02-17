# Billing SaaS – Project Scope & Developer Guide

One document to understand the full project: what we build, for whom, and how it works.

---

## 1. What is this project?

- **Multi-tenant Billing SaaS** – Many companies (organizations) use one platform; each company’s data is isolated.
- **API only** – No UI. Backend for:
  - Mobile apps (iOS/Android)
  - Web apps (React, Vue, etc.)
  - Any client that can call REST APIs
- **Framework:** Laravel 11, MySQL, Laravel Sanctum (Bearer token auth).

---

## 2. Project phases (scope)

### Phase 1 – Foundation

| Area | What we have |
|------|------------------|
| **Super Admin** | Platform owner; can list/manage organizations and see plans. |
| **Organizations** | Company signup (register), profile, settings. |
| **Subscription plans** | Plans with billing cycle, invoice limit, price, email/payment flags. |
| **Organization subscription** | Each org has an active subscription (start/end date, status). |
| **Users & roles** | Users belong to an org (or null for super_admin). Roles: super_admin, org_owner, org_user. |
| **Usage tracking** | Separate `usage_tracking` table – no counters on master tables. Used to enforce invoice limits. |
| **Audit** | `audit_logs` table (user, organization, action, ip). |

### Phase 2 – Billing core

| Area | What we have |
|------|------------------|
| **Sales (outgoing)** | Customers, Invoices (+ items), Proforma Invoices, Quotations, Credit Notes, Debit Notes, Delivery Challans. |
| **Purchase (incoming)** | Vendors, Purchase Orders, Purchase Bills, Vendor Credit Notes. |
| **Statuses** | Each document has a status (draft, sent, paid, etc.) – ready for later email/payment hooks. |
| **No email/payment yet** | No sending emails or processing payments in code; structure is ready to plug in later. |

---

## 3. Roles (who can do what)

| Role | Description | Access |
|------|-------------|--------|
| **super_admin** | Platform owner | All data. Uses `/api/super/*`. For `/api/org/*` must send `X-Organization-Id`. |
| **org_owner** | Company owner | Full access to **their** organization only: users, customers, invoices, profile, etc. |
| **org_user** | Staff | Same as org_owner for **their** organization (no extra restriction in this scope). |

Roles are stored in `roles` table; users have `role_id`. Slugs: `super_admin`, `org_owner`, `org_user`.

---

## 4. Multi-tenancy rule (important)

- **Every request** (except super_admin-only routes) is **scoped by organization**.
- **org_owner / org_user:** Current organization = logged-in user’s `organization_id`. They cannot see or change another org’s data.
- **super_admin:** Can act as any org by sending **`X-Organization-Id: {id}`** on `/api/org/*` requests.
- **No data leakage** between organizations; all org-scoped queries filter by `organization_id`.

---

## 5. Database at a glance

### Phase 1 – Foundation

| Table | Purpose |
|-------|---------|
| **users** | id, organization_id (nullable for super_admin), role_id, name, email, phone, password, status |
| **organizations** | id, organization_code, company_name, legal_name, email, phone, gstin, address, status |
| **roles** | id, name, slug (super_admin, org_owner, org_user) |
| **subscription_plans** | id, plan_name, billing_cycle, invoice_limit, price, email_feature, payment_feature, status |
| **organization_subscriptions** | id, organization_id, subscription_plan_id, start_date, end_date, status |
| **usage_tracking** | id, organization_id, subscription_id, usage_type (invoice/purchase), reference_id, usage_month (YYYY-MM) – **no updated_at** |
| **organization_settings** | id, organization_id, email_enabled, payment_enabled, smtp_configured, payment_configured |
| **audit_logs** | id, user_id, organization_id, action, ip_address, created_at |

### Phase 2 – Billing

| Table | Purpose |
|-------|---------|
| **customers** | Org’s customers (name, email, phone, gstin, billing_address, status) |
| **invoices** | Sales invoices (customer_id, invoice_no, type, dates, amounts, status) |
| **invoice_items** | Line items per invoice |
| **proforma_invoices** | Proforma docs (customer, pi_no, dates, amounts, status) |
| **quotations** | Quotations (customer, quotation_no, dates, amounts, status) |
| **credit_notes** | Sales credit notes (invoice_id, amount, reason) |
| **debit_notes** | Sales debit notes (invoice_id, amount, reason) |
| **delivery_challans** | Challans (customer_id, challan_no, date, status) |
| **vendors** | Org’s vendors |
| **purchase_orders** | POs (vendor_id, po_no, dates, amounts, status) |
| **purchase_bills** | Bills from vendors (vendor_id, bill_no, dates, amounts, status) |
| **vendor_credit_notes** | Credit notes from vendors |

All Phase 2 tables have **organization_id** and are queried with org scope.

---

## 6. Key business rules

### Usage tracking (invoice limit)

- **No counters on master tables.** We do **not** store “invoice count” on organizations or subscriptions.
- When an **invoice is created**, we **insert one row** into `usage_tracking`:
  - `organization_id`, `subscription_id`, `usage_type = 'invoice'`, `reference_id = invoice.id`, `usage_month = current YYYY-MM`.
- To **enforce limit:**  
  Count rows in `usage_tracking` where `organization_id`, `usage_type = 'invoice'`, and `usage_month = current month`.  
  Compare with `subscription_plans.invoice_limit`. If count ≥ limit → **block** creating the invoice (e.g. 422).
- Logic lives in **UsageTrackingService** and **InvoiceService**.

### Organization subscription

- Each organization has an **active** subscription (status active, end_date ≥ today).
- Registration creates org + first user (org_owner) + subscription (from chosen plan) + organization_settings.
- Subscription lifecycle (renew, cancel, expire) is in **SubscriptionService**; status can be active/expired/cancelled.

### Registration email

- On **organization register**, a **registration success** email is sent to the **organization email** with company details, owner name, **login email (username)** and **password**, and site link (from `APP_URL` or `APP_FRONTEND_URL`).

### Forgot / reset password

- **Forgot:** `POST /api/forgot-password` with email → reset link sent by email (link URL configurable via `FRONTEND_PASSWORD_RESET_URL`).
- **Reset:** `POST /api/password/reset` with email, token from link, new password, confirmation.

---

## 7. Architecture (for developers)

| Layer | Where | Responsibility |
|-------|--------|----------------|
| **Routes** | `routes/api.php` | Define method, path, middleware (auth:sanctum, super_admin, organization.scope). |
| **Controllers** | `app/Http/Controllers/Api/` | Thin: validate input, call services or models, return JSON (resources or simple arrays). |
| **Services** | `app/Services/` | Business logic: **SubscriptionService**, **UsageTrackingService**, **InvoiceService** (create/update invoice, enforce limit, record usage). |
| **Models** | `app/Models/` | Eloquent models and relationships (User, Organization, Invoice, Customer, etc.). |
| **Resources** | `app/Http/Resources/` | Shape API responses (UserResource, OrganizationResource, InvoiceResource, etc.). |
| **Middleware** | `app/Http/Middleware/` | **EnsureSuperAdmin**, **EnsureOrganizationScope** (set current org from user or `X-Organization-Id`). |

- **Policies:** Can be added later for fine-grained permission checks.
- **No UI:** No Blade/Vue/React in this repo; only API + database + core logic.

---

## 8. Auth flow (short)

1. **Login:** `POST /api/login` → returns `token`. Use **Bearer {token}** for all protected routes.
2. **Register:** `POST /api/organizations/register` → creates org + owner + subscription + settings, sends email, returns `token` for owner.
3. **Protected routes:** Require `Authorization: Bearer {token}`. Org routes use current user’s org (or `X-Organization-Id` for super_admin).

---

## 9. Where to find what

| Need | Document / Place |
|------|-------------------|
| **Full project scope** (this doc) | `docs/PROJECT-SCOPE.md` |
| **Every API route** (method, path, body, response, errors) | `docs/API.md` |
| **Quick start, roles, main endpoints** | `README.md` |
| **Postman** (import, env, token) | `postman/README.md` + `postman/*.json` |

---

## 10. Quick start (developer)

```bash
# 1. Env and key
cp .env.example .env && php artisan key:generate

# 2. DB (e.g. MySQL in .env)
php artisan migrate
php artisan db:seed

# 3. Run API
php artisan serve
# Base URL: http://localhost:8000/api
```

- **Test:** Use Postman; select environment, run Login (Org Owner - Demo), then call any `/api/org/*` request. Token is saved automatically in the collection.
- **Test users:** super_admin `superadmin@billing.test` / `password`; org owner `owner@demo.com` / `password`.

---

## 11. Summary

- **One platform, many organizations** – multi-tenant, org-scoped.
- **API only** – no UI; clients use REST + Bearer token.
- **Phase 1:** Super admin, orgs, plans, subscriptions, users/roles, usage tracking, audit.
- **Phase 2:** Customers, invoices (with limit), proforma, quotations, credit/debit notes, challans, vendors, purchase orders/bills, vendor credit notes.
- **Rules:** Org-scope on every non–super_admin request; invoice limit via `usage_tracking`; subscription and registration emails as described above.
- **Docs:** This file = project scope; `docs/API.md` = full route reference for developers.
