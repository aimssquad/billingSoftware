# Postman – Billing SaaS API

## 1. Import

- **Collection:** `Billing-SaaS-API.postman_collection.json`
- **Environment:** `Billing-SaaS-Local.postman_environment.json`

## 2. Select environment

In the top-right dropdown, select **Billing SaaS - Local**. Without this, `{{base_url}}` and `{{token}}` are empty.

## 3. Token (automatic)

- Run **Auth → Login (Super Admin)** or **Login (Org Owner - Demo)**. The **Tests** script saves the response `token` to the environment.
- All other requests use **Bearer {{token}}** from the collection auth. No need to copy the token manually.

## 4. Collection overview

| Folder | Requests |
|--------|----------|
| **Auth** | Login (Super Admin), Login (Org Owner - Demo), Logout |
| **Public** | Organization Register, Forgot Password, Reset Password |
| **Me** | Get current user |
| **Super Admin** | List Plans, List Organizations, Show Organization |
| **Org Scoped** | Org Me, Organization Profile (GET), Update Profile (PUT), Usage, Subscription, List Roles, Users (List, Create), Customers (List, Create, Show), Invoices (List, Create, Show, Get Items), Vendors (List, Create) |

## 5. Environment variables

| Variable | Purpose |
|----------|---------|
| `base_url` | API base URL (e.g. `http://localhost:8000/api`) |
| `token` | Set automatically after Login |

## 6. Test accounts

- **Super Admin:** `superadmin@billing.test` / `password`
- **Demo org owner:** `owner@demo.com` / `password`

## 7. Org-scoped IDs

For **Show Customer**, **Show Invoice**, **Get Invoice Items** etc., replace `1` in the URL with a real ID from your List responses (e.g. `{{base_url}}/org/customers/2`).
