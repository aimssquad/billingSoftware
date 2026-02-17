# Billing SaaS API – Developer Documentation

Multi-tenant billing API. All routes are prefixed with `/api`. Authentication uses **Laravel Sanctum** (Bearer token).

---

## Table of contents

1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Public routes](#public-routes)
4. [Authenticated routes (common)](#authenticated-routes-common)
5. [Super Admin routes](#super-admin-routes)
6. [Organization-scoped routes](#organization-scoped-routes)
7. [Errors](#errors)

---

## Overview

| Item | Value |
|------|--------|
| **Base URL** | `http://localhost:8000/api` (or your `APP_URL` + `/api`) |
| **Auth** | Bearer token in header: `Authorization: Bearer {token}` |
| **Content type** | `Accept: application/json` and `Content-Type: application/json` for request bodies |
| **Pagination** | List endpoints return `data`, `links`, `meta` (Laravel default). Page size is usually 15. |

### Roles

- **super_admin** – Platform owner; can access `/api/super/*`.
- **org_owner** / **org_user** – Belong to an organization; use `/api/org/*`. Super admin can also call org routes by sending `X-Organization-Id: {id}`.

---

## Authentication

### Obtaining a token

- **Login:** `POST /api/login` with `email` and `password`. Response includes `token`. Use this as the Bearer token for all protected routes.
- **Organization register:** `POST /api/organizations/register` returns `token` for the new org owner.

### Org-scoped routes

- For **org_owner** / **org_user**, the current organization is taken from the authenticated user.
- For **super_admin**, you must send header: **`X-Organization-Id: {organization_id}`** when calling any `/api/org/*` route.

---

## Public routes

No `Authorization` header required.

---

### POST /api/login

Log in with email and password. Returns a Bearer token.

**Request body**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | User email |
| password | string | Yes | Password |

**Response** `200`

```json
{
  "message": "Login successful.",
  "user": {
    "id": 1,
    "organization_id": 1,
    "role": "org_owner",
    "role_name": "Organization Owner",
    "name": "Demo Owner",
    "email": "owner@demo.com",
    "phone": "9876543211",
    "status": "active",
    "created_at": "2025-02-09T10:00:00.000000Z"
  },
  "token": "1|abc...",
  "token_type": "Bearer"
}
```

**Errors:** `422` – invalid credentials or inactive account.

---

### POST /api/organizations/register

Register a new organization and its first user (org_owner). Sends a registration-success email to the organization email. Returns token for the new user.

**Request body**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_name | string | Yes | Company name |
| legal_name | string | No | Legal name |
| email | string | Yes | Organization email (receives registration email) |
| phone | string | No | Organization phone |
| gstin | string | No | GSTIN |
| address | string | No | Address |
| owner_name | string | Yes | Owner full name |
| owner_email | string | Yes | Owner login email (unique) |
| owner_password | string | Yes | Min 8 characters |
| owner_phone | string | No | Owner phone |
| subscription_plan_id | integer | Yes | ID from subscription plans (e.g. from GET /api/super/plans) |

**Response** `201`

```json
{
  "message": "Organization registered successfully.",
  "organization": { "id": 1, "organization_code": "ABC12XYZ", "company_name": "...", "email": "...", "status": "active", ... },
  "user": { "id": 1, "role": "org_owner", "name": "...", "email": "...", ... },
  "token": "2|xyz...",
  "token_type": "Bearer"
}
```

**Errors:** `422` – validation or plan not active.

---

### POST /api/forgot-password

Request a password reset link. Sends an email if the address exists (response is generic for security).

**Request body**

| Field | Type | Required |
|-------|------|----------|
| email | string | Yes |

**Response** `200`

```json
{
  "message": "If that email exists, we have sent a password reset link."
}
```

---

### POST /api/password/reset

Reset password using the token from the forgot-password email.

**Request body**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | User email |
| token | string | Yes | Token from reset link |
| password | string | Yes | New password (min 8) |
| password_confirmation | string | Yes | Must match password |

**Response** `200`

```json
{
  "message": "Password has been reset. You can now login."
}
```

**Errors:** `422` – invalid/expired token or validation.

---

## Authenticated routes (common)

Require header: `Authorization: Bearer {token}`.

---

### GET /api/user

Return the authenticated user.

**Response** `200`

```json
{
  "data": {
    "id": 1,
    "organization_id": 1,
    "role": "org_owner",
    "role_name": "Organization Owner",
    "name": "Demo Owner",
    "email": "owner@demo.com",
    "phone": "9876543211",
    "status": "active",
    "created_at": "2025-02-09T10:00:00.000000Z"
  }
}
```

---

### POST /api/logout

Revoke the current access token.

**Response** `200`

```json
{
  "message": "Logged out."
}
```

---

## Super Admin routes

Require **Bearer token** and user role **super_admin**.

---

### GET /api/super/plans

List active subscription plans.

**Response** `200`

```json
{
  "data": [
    {
      "id": 1,
      "plan_name": "Starter",
      "billing_cycle": "monthly",
      "invoice_limit": 10,
      "price": "299.00",
      "email_feature": false,
      "payment_feature": false,
      "status": "active"
    }
  ]
}
```

---

### GET /api/super/organizations

List all organizations. Paginated.

**Query**

| Param | Type | Description |
|-------|------|-------------|
| status | string | Filter: `active`, `suspended`, `closed` |
| page | integer | Page number |

**Response** `200`

```json
{
  "data": [ { "id": 1, "organization_code": "...", "company_name": "...", "email": "...", "status": "active", ... } ],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": { "current_page": 1, "per_page": 15, "total": 1 }
}
```

---

### GET /api/super/organizations/{id}

Get one organization by ID (with active subscription and settings).

**Response** `200`

```json
{
  "data": {
    "id": 1,
    "organization_code": "DEMO001",
    "company_name": "Demo Company Pvt Ltd",
    "legal_name": "Demo Company Private Limited",
    "email": "demo@example.com",
    "phone": "9876543210",
    "gstin": "29AABCU9603R1ZM",
    "address": "123 Demo Street",
    "status": "active",
    "created_at": "..."
  }
}
```

**Errors:** `404` – organization not found.

---

## Organization-scoped routes

Require **Bearer token** and **organization scope** (org_owner/org_user, or super_admin with `X-Organization-Id`). All resources are scoped to that organization.

---

### GET /api/org/me

Current user and current organization summary.

**Response** `200`

```json
{
  "user": { "id": 1, "organization_id": 1, "role": "org_owner", "name": "...", "email": "...", "status": "active", ... },
  "organization": { "id": 1, "organization_code": "...", "company_name": "...", "email": "...", "status": "active", ... }
}
```

---

### GET /api/org/profile

Organization profile including invoice/letterhead fields, settings, subscription, and usage. Use for dashboard and invoice templates.

**Response** `200`

```json
{
  "organization": {
    "id": 1,
    "organization_code": "DEMO001",
    "company_name": "Demo Company Pvt Ltd",
    "legal_name": "Demo Company Private Limited",
    "email": "demo@example.com",
    "phone": "9876543210",
    "gstin": "29AABCU9603R1ZM",
    "address": "123 Demo Street",
    "status": "active",
    "created_at": "...",
    "invoice_display_name": "Demo Company Private Limited",
    "invoice_address": "123 Demo Street",
    "invoice_email": "demo@example.com",
    "invoice_phone": "9876543210",
    "invoice_gstin": "29AABCU9603R1ZM"
  },
  "settings": {
    "email_enabled": true,
    "payment_enabled": false,
    "smtp_configured": false,
    "payment_configured": false
  },
  "subscription": {
    "id": 1,
    "status": "active",
    "start_date": "2025-02-01",
    "end_date": "2025-02-28",
    "plan": {
      "id": 2,
      "plan_name": "Professional",
      "billing_cycle": "monthly",
      "invoice_limit": 100,
      "price": 799
    }
  },
  "usage": {
    "usage_month": "2025-02",
    "invoice_count": 5,
    "invoice_limit": 100,
    "invoice_remaining": 95,
    "purchase_count": 0
  }
}
```

---

### PUT /api/org/profile

Update organization profile (company name, legal name, email, phone, gstin, address). Used for invoice/letterhead details.

**Request body** (all optional)

| Field | Type | Description |
|-------|------|-------------|
| company_name | string | Max 255 |
| legal_name | string | Max 255 |
| email | string | Email |
| phone | string | Max 20 |
| gstin | string | Max 20 |
| address | string | Text |

**Response** `200`

```json
{
  "message": "Profile updated.",
  "organization": {
    "id": 1,
    "organization_code": "...",
    "company_name": "...",
    "legal_name": "...",
    "email": "...",
    "phone": "...",
    "gstin": "...",
    "address": "...",
    "invoice_display_name": "...",
    "invoice_address": "...",
    "invoice_email": "...",
    "invoice_phone": "...",
    "invoice_gstin": "..."
  }
}
```

---

### GET /api/org/usage

Current month usage and invoice limit.

**Response** `200`

```json
{
  "usage": {
    "usage_month": "2025-02",
    "invoice_count": 5,
    "purchase_count": 0
  },
  "invoice_limit": 100,
  "invoice_remaining": 95
}
```

---

### GET /api/org/subscription

Active subscription and plan for the organization.

**Response** `200`

```json
{
  "subscription": {
    "id": 1,
    "start_date": "2025-02-01",
    "end_date": "2025-02-28",
    "status": "active",
    "plan": {
      "id": 2,
      "plan_name": "Professional",
      "billing_cycle": "monthly",
      "invoice_limit": 100
    }
  }
}
```

**Errors:** `404` – no active subscription.

---

### GET /api/org/roles

Roles that can be assigned to users in the organization (org_owner, org_user).

**Response** `200`

```json
{
  "data": [
    { "id": 2, "name": "Organization Owner", "slug": "org_owner" },
    { "id": 3, "name": "Organization User", "slug": "org_user" }
  ]
}
```

---

### Users (org)

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/users | List users (paginated) |
| POST | /api/org/users | Create user |
| GET | /api/org/users/{id} | Show user |
| PUT/PATCH | /api/org/users/{id} | Update user |
| DELETE | /api/org/users/{id} | Delete user |

**POST /api/org/users** body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Max 255 |
| email | string | Yes | Unique |
| phone | string | No | Max 20 |
| password | string | Yes | Min 8 |
| role_id | integer | Yes | From GET /api/org/roles (2 = org_owner, 3 = org_user) |

**PUT/PATCH** body (all optional): `name`, `phone`, `password`, `status` (`active`|`inactive`|`suspended`).

---

### Customers

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/customers | List customers (paginated) |
| POST | /api/org/customers | Create customer |
| GET | /api/org/customers/{id} | Show customer |
| PUT/PATCH | /api/org/customers/{id} | Update customer |
| DELETE | /api/org/customers/{id} | Delete customer |

**POST /api/org/customers** body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Max 255 |
| email | string | No | Email |
| phone | string | No | Max 20 |
| gstin | string | No | Max 20 |
| billing_address | string | No | Text |

**PUT/PATCH** body (all optional): `name`, `email`, `phone`, `gstin`, `billing_address`, `status` (`active`|`inactive`).

**Response (list/show):** `{ "data": [ ... ] }` or `{ "data": { ... } }`. Delete returns `204` no content.

---

### Invoices

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/invoices | List invoices (paginated) |
| POST | /api/org/invoices | Create invoice (enforces plan limit) |
| GET | /api/org/invoices/{id} | Show invoice (with customer and items) |
| PUT/PATCH | /api/org/invoices/{id} | Update invoice |
| DELETE | /api/org/invoices/{id} | Delete invoice |
| GET | /api/org/invoices/{id}/items | Get invoice line items |

**POST /api/org/invoices** body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| customer_id | integer | Yes | Must belong to org |
| invoice_no | string | Yes | Max 50 |
| invoice_type | string | No | `gst` or `non_gst` (default gst) |
| invoice_date | string | Yes | Date (Y-m-d) |
| due_date | string | No | Date (Y-m-d) |
| subtotal | number | No | Default 0 |
| tax_amount | number | No | Default 0 |
| discount_amount | number | No | Default 0 |
| total_amount | number | No | Default 0 |
| status | string | No | `draft`\|`sent`\|`partial`\|`paid`\|`overdue` (default draft) |
| items | array | No | Line items (see below) |

**items[]** (each element):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| item_name | string | Yes | |
| quantity | number | No | Default 1 |
| price | number | No | Default 0 |
| tax_percent | number | No | Default 0 |
| tax_amount | number | No | Default 0 |
| total_amount | number | No | Default 0 |

**Response (show/list):** Invoice resource with `id`, `organization_id`, `customer_id`, `customer` (nested), `invoice_no`, `invoice_type`, `invoice_date`, `due_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `status`, `items` (when loaded), `created_at`.

**Errors:** `422` – customer not in org, or **invoice limit reached for the month** (message in body).

---

### Proforma Invoices

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/proforma-invoices | List (paginated) |
| POST | /api/org/proforma-invoices | Create |
| GET | /api/org/proforma-invoices/{id} | Show |
| PUT/PATCH | /api/org/proforma-invoices/{id} | Update |
| DELETE | /api/org/proforma-invoices/{id} | Delete |

**POST body:** `customer_id` (required), `pi_no`, `pi_date`, `valid_till`, `subtotal`, `tax_amount`, `total_amount`, `status` (`draft`|`sent`|`converted`).

---

### Quotations

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/quotations | List (paginated) |
| POST | /api/org/quotations | Create |
| GET | /api/org/quotations/{id} | Show |
| PUT/PATCH | /api/org/quotations/{id} | Update |
| DELETE | /api/org/quotations/{id} | Delete |

**POST body:** `customer_id` (required), `quotation_no`, `quotation_date`, `valid_till`, `subtotal`, `tax_amount`, `total_amount`, `status` (`draft`|`sent`|`accepted`|`rejected`).

---

### Credit Notes (sales)

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/credit-notes | List (paginated) |
| POST | /api/org/credit-notes | Create |
| GET | /api/org/credit-notes/{id} | Show |
| PUT/PATCH | /api/org/credit-notes/{id} | Update |
| DELETE | /api/org/credit-notes/{id} | Delete |

**POST body:** `invoice_id` (required), `credit_note_no`, `amount` (required, ≥0), `reason`.

---

### Debit Notes (sales)

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/debit-notes | List (paginated) |
| POST | /api/org/debit-notes | Create |
| GET | /api/org/debit-notes/{id} | Show |
| PUT/PATCH | /api/org/debit-notes/{id} | Update |
| DELETE | /api/org/debit-notes/{id} | Delete |

**POST body:** `invoice_id` (required), `debit_note_no`, `amount` (required, ≥0), `reason`.

---

### Delivery Challans

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/delivery-challans | List (paginated) |
| POST | /api/org/delivery-challans | Create |
| GET | /api/org/delivery-challans/{id} | Show |
| PUT/PATCH | /api/org/delivery-challans/{id} | Update |
| DELETE | /api/org/delivery-challans/{id} | Delete |

**POST body:** `customer_id` (required), `challan_no`, `challan_date`, `status` (`open`|`delivered`).

---

### Vendors

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/vendors | List (paginated) |
| POST | /api/org/vendors | Create |
| GET | /api/org/vendors/{id} | Show |
| PUT/PATCH | /api/org/vendors/{id} | Update |
| DELETE | /api/org/vendors/{id} | Delete |

**POST body:** `name` (required), `email`, `phone`, `gstin`, `address`. **PUT/PATCH:** same fields + `status` (`active`|`inactive`).

---

### Purchase Orders

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/purchase-orders | List (paginated) |
| POST | /api/org/purchase-orders | Create |
| GET | /api/org/purchase-orders/{id} | Show |
| PUT/PATCH | /api/org/purchase-orders/{id} | Update |
| DELETE | /api/org/purchase-orders/{id} | Delete |

**POST body:** `vendor_id` (required), `po_no`, `po_date`, `subtotal`, `tax_amount`, `total_amount`, `status` (`draft`|`sent`|`received`).

---

### Purchase Bills

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/purchase-bills | List (paginated) |
| POST | /api/org/purchase-bills | Create |
| GET | /api/org/purchase-bills/{id} | Show |
| PUT/PATCH | /api/org/purchase-bills/{id} | Update |
| DELETE | /api/org/purchase-bills/{id} | Delete |

**POST body:** `vendor_id` (required), `bill_no`, `bill_date`, `subtotal`, `tax_amount`, `total_amount`, `status` (`unpaid`|`paid`).

---

### Vendor Credit Notes

| Method | Path | Description |
|--------|------|-------------|
| GET | /api/org/vendor-credit-notes | List (paginated) |
| POST | /api/org/vendor-credit-notes | Create |
| GET | /api/org/vendor-credit-notes/{id} | Show |
| PUT/PATCH | /api/org/vendor-credit-notes/{id} | Update |
| DELETE | /api/org/vendor-credit-notes/{id} | Delete |

**POST body:** `vendor_id` (required), `credit_note_no`, `amount` (required, ≥0), `reason`.

---

## Errors

- **401 Unauthorized** – Missing or invalid Bearer token.
- **403 Forbidden** – Valid token but not allowed (e.g. non–super_admin on `/api/super/*`).
- **404 Not Found** – Resource not found or not in current organization.
- **422 Unprocessable Entity** – Validation errors or business rule (e.g. invoice limit). Body usually includes `message` and/or `errors` (validation).

**Validation error example:**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

**Business rule example:**

```json
{
  "message": "Invoice limit reached for this month. Upgrade your plan or wait for next billing cycle."
}
```
