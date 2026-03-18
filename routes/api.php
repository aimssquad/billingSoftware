<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Org\OrganizationMailSettingController;
use App\Http\Controllers\Api\Org\InvoiceController;
use App\Services\TenantMailService;
use App\Mail\InvoiceSentMail;
use App\Services\TenantMailConfigService; 
use App\Http\Controllers\Api\Org\OrganizationPaymentGatewayController;
use App\Http\Controllers\Api\Org\PaymentHistoryController;  
use App\Http\Controllers\Api\Org\PaymentController;
use App\Http\Controllers\Api\Org\SubscriptionController;
use App\Http\Controllers\Api\Org\BankAccountController;
use App\Http\Controllers\Api\Org\OrganizationInvoiceSettingController;
use App\Http\Controllers\Api\SuperAdmin\SubscriptionPlanController;
use App\Http\Controllers\Api\SuperAdmin\CountryFieldController;
use App\Http\Controllers\Api\SuperAdmin\InvoiceTemplateController;
use App\Http\Controllers\Api\SuperAdmin\CountryController;


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
Route::get('countries-active', [CountryController::class, 'active']);



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
        Route::apiResource('subscription-plans', SubscriptionPlanController::class);

        Route::get('country-fields', [CountryFieldController::class, 'index']);
        Route::post('country-fields', [CountryFieldController::class, 'store']);
        Route::put('country-fields/{id}', [CountryFieldController::class, 'update']);
        Route::delete('country-fields/{id}', [CountryFieldController::class, 'destroy']);

        Route::get('country-fields/by-country/{country}', [CountryFieldController::class, 'getByCountry']);

        Route::get('invoice-templates', [InvoiceTemplateController::class, 'index']);
        Route::post('invoice-templates', [InvoiceTemplateController::class, 'store']);
        Route::get('invoice-templates/{id}', [InvoiceTemplateController::class, 'show']);
        Route::post('invoice-templates/{id}', [InvoiceTemplateController::class, 'update']);
        Route::delete('invoice-templates/{id}', [InvoiceTemplateController::class, 'destroy']);

        Route::apiResource('countries', CountryController::class);
    });

    // Organization-scoped (org_owner, org_user) - require organization in context
    Route::middleware('organization.scope')->prefix('org')->group(function () {

        Route::get('/me', [App\Http\Controllers\Api\Org\MeController::class, 'show']);
        Route::get('/profile', [App\Http\Controllers\Api\Org\ProfileController::class, 'show']);
        Route::post('/profile', [App\Http\Controllers\Api\Org\ProfileController::class, 'update']);
        Route::get('/usage', [App\Http\Controllers\Api\Org\UsageController::class, 'index']);
        Route::get('/subscription', [App\Http\Controllers\Api\Org\SubscriptionController::class, 'show']);
        Route::post('/subscription', [SubscriptionController::class, 'store']);
        Route::patch('subscription/{id}', [SubscriptionController::class, 'edit']);
        Route::put('subscription/{id}', [SubscriptionController::class, 'update']);
        

        Route::get('/roles', [App\Http\Controllers\Api\Org\RoleController::class, 'index']);

        // Users (org)
        Route::apiResource('users', App\Http\Controllers\Api\Org\UserController::class);

        // Customers
        Route::get('/customers/all', [App\Http\Controllers\Api\Org\CustomerController::class, 'allCustomers']);
        Route::apiResource('customers', App\Http\Controllers\Api\Org\CustomerController::class);
        

        // Invoices (with limit check in service)
        Route::get('/invoices/all',[InvoiceController::class, 'invoiceList']);
        Route::get('/invoices/last-invoice-no', [InvoiceController::class, 'orgLastInvoiceNo']);
        Route::apiResource('invoices', App\Http\Controllers\Api\Org\InvoiceController::class);
        Route::post('/invoices/{invoice}/send-email',[InvoiceController::class, 'sendEmail']);
        Route::get('invoices/{invoice}/items', [App\Http\Controllers\Api\Org\InvoiceController::class, 'items']);

        // Other sales documents (simple CRUD for now)
        Route::apiResource('proforma-invoices', App\Http\Controllers\Api\Org\ProformaInvoiceController::class)->parameters(['proforma-invoices' => 'proforma_invoice']);
        Route::apiResource('quotations', App\Http\Controllers\Api\Org\QuotationController::class);
        Route::apiResource('credit-notes', App\Http\Controllers\Api\Org\CreditNoteController::class)->parameters(['credit-notes' => 'credit_note']);
        Route::apiResource('debit-notes', App\Http\Controllers\Api\Org\DebitNoteController::class)->parameters(['debit-notes' => 'debit_note']);
        Route::apiResource('delivery-challans', App\Http\Controllers\Api\Org\DeliveryChallanController::class)->parameters(['delivery-challans' => 'delivery_challan']);

        // Purchase side
        Route::get('/vendors/all', [App\Http\Controllers\Api\Org\VendorController::class, 'allVendors']);
        Route::apiResource('vendors', App\Http\Controllers\Api\Org\VendorController::class);
        Route::apiResource('purchase-orders', App\Http\Controllers\Api\Org\PurchaseOrderController::class)->parameters(['purchase-orders' => 'purchase_order']);
        Route::apiResource('purchase-bills', App\Http\Controllers\Api\Org\PurchaseBillController::class)->parameters(['purchase-bills' => 'purchase_bill']);
        Route::apiResource('vendor-credit-notes', App\Http\Controllers\Api\Org\VendorCreditNoteController::class)->parameters(['vendor-credit-notes' => 'vendor_credit_note']);

        // Organization Email settings
        Route::get('/mail-settings', [OrganizationMailSettingController::class, 'show']);
        Route::post('/mail-settings', [OrganizationMailSettingController::class, 'storeOrUpdate']);
        Route::delete('/mail-settings', [OrganizationMailSettingController::class, 'destroy']);

        Route::get('/gateways', [OrganizationPaymentGatewayController::class, 'index']);
        Route::post('/gateways', [OrganizationPaymentGatewayController::class, 'store']);
        Route::post('/gateways/{id}/activate', [OrganizationPaymentGatewayController::class, 'activate']);
        Route::delete('/gateways/{id}', [OrganizationPaymentGatewayController::class, 'destroy']);

        Route::post('/pay', [PaymentController::class, 'pay']);
        Route::post('/webhook/stripe', [PaymentController::class, 'stripeWebhook']);
        Route::post('/webhook/razorpay', [PaymentController::class, 'razorpayWebhook']);
        Route::post('/paypal/capture', [PaymentController::class, 'paypalCapture']);

        Route::get('/payments', [PaymentHistoryController::class, 'index']);
        Route::get('/payments/{id}', [PaymentHistoryController::class, 'show']);

        // Bank Details
        Route::prefix('bank-accounts')->group(function(){
            Route::get('/',[BankAccountController::class,'index']);
            Route::post('/',[BankAccountController::class,'store']);
            Route::get('/{id}',[BankAccountController::class,'show']);
            Route::post('/{id}',[BankAccountController::class,'update']);
            Route::post('/{id}/make-primary', [BankAccountController::class, 'makePrimary']);
            Route::delete('/{id}',[BankAccountController::class,'destroy']);
        });

        Route::get('invoice-templates', [InvoiceTemplateController::class, 'orgIndex']);
        Route::get('/invoice-setting', [OrganizationInvoiceSettingController::class,'show']);
        Route::post('/invoice-setting', [OrganizationInvoiceSettingController::class,'store']);
        Route::post('/invoice-setting/template', [OrganizationInvoiceSettingController::class,'updateTemplate']);



    });
});
