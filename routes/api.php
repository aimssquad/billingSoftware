<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Multi-Tenant Billing SaaS
|--------------------------------------------------------------------------
| All routes are prefixed with /api
| Auth: Sanctum (Bearer token)
*/

// Public (no auth)
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/organizations/register', [App\Http\Controllers\Api\OrganizationController::class, 'register']);
Route::post('/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);

// Protected (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    });

    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Super Admin only
    Route::middleware('super_admin')->prefix('super')->group(function () {
        Route::get('/organizations', [App\Http\Controllers\Api\SuperAdmin\OrganizationController::class, 'index']);
        Route::get('/organizations/{organization}', [App\Http\Controllers\Api\SuperAdmin\OrganizationController::class, 'show']);
        Route::get('/plans', [App\Http\Controllers\Api\SuperAdmin\SubscriptionPlanController::class, 'index']);
    });

    // Organization-scoped (org_owner, org_user) - require organization in context
    Route::middleware('organization.scope')->prefix('org')->group(function () {

        Route::get('/me', [App\Http\Controllers\Api\Org\MeController::class, 'show']);
        Route::get('/profile', [App\Http\Controllers\Api\Org\ProfileController::class, 'show']);
        Route::put('/profile', [App\Http\Controllers\Api\Org\ProfileController::class, 'update']);
        Route::get('/usage', [App\Http\Controllers\Api\Org\UsageController::class, 'index']);
        Route::get('/subscription', [App\Http\Controllers\Api\Org\SubscriptionController::class, 'show']);

        Route::get('/roles', [App\Http\Controllers\Api\Org\RoleController::class, 'index']);

        // Users (org)
        Route::apiResource('users', App\Http\Controllers\Api\Org\UserController::class);

        // Customers
        Route::apiResource('customers', App\Http\Controllers\Api\Org\CustomerController::class);

        // Invoices (with limit check in service)
        Route::apiResource('invoices', App\Http\Controllers\Api\Org\InvoiceController::class);
        Route::get('invoices/{invoice}/items', [App\Http\Controllers\Api\Org\InvoiceController::class, 'items']);

        // Other sales documents (simple CRUD for now)
        Route::apiResource('proforma-invoices', App\Http\Controllers\Api\Org\ProformaInvoiceController::class)->parameters(['proforma-invoices' => 'proforma_invoice']);
        Route::apiResource('quotations', App\Http\Controllers\Api\Org\QuotationController::class);
        Route::apiResource('credit-notes', App\Http\Controllers\Api\Org\CreditNoteController::class)->parameters(['credit-notes' => 'credit_note']);
        Route::apiResource('debit-notes', App\Http\Controllers\Api\Org\DebitNoteController::class)->parameters(['debit-notes' => 'debit_note']);
        Route::apiResource('delivery-challans', App\Http\Controllers\Api\Org\DeliveryChallanController::class)->parameters(['delivery-challans' => 'delivery_challan']);

        // Purchase side
        Route::apiResource('vendors', App\Http\Controllers\Api\Org\VendorController::class);
        Route::apiResource('purchase-orders', App\Http\Controllers\Api\Org\PurchaseOrderController::class)->parameters(['purchase-orders' => 'purchase_order']);
        Route::apiResource('purchase-bills', App\Http\Controllers\Api\Org\PurchaseBillController::class)->parameters(['purchase-bills' => 'purchase_bill']);
        Route::apiResource('vendor-credit-notes', App\Http\Controllers\Api\Org\VendorCreditNoteController::class)->parameters(['vendor-credit-notes' => 'vendor_credit_note']);
    });
});
