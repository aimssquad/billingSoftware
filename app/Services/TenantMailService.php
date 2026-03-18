<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\OrganizationMailSetting;
use App\Models\OrganizationInvoiceSetting;
use App\Mail\InvoiceSentMail;

class TenantMailService
{
    public static function send($organizationId, $invoice, $to,$paymentLink = null)
    {
        $mailSetting = OrganizationMailSetting::where('organization_id', $organizationId)
            ->where('is_active', true)
            ->first();

        if (!$mailSetting) {
            throw new \Exception("Mail setting not found");
        }  
        
        $invoiceSetting = OrganizationInvoiceSetting::with('template')
            ->where('organization_id', $organizationId)
            ->first();

        $templateSlug = $invoiceSetting?->template?->slug ?? 'default';
        //dd($organizationId);
        TenantMailConfigService::configure($organizationId);

        Mail::mailer('tenant')
            ->to($to)
            ->send(
                new InvoiceSentMail(
                    $invoice,
                    config('app.frontend_url'),
                    $mailSetting->from_address,
                    $mailSetting->from_name,
                    $templateSlug, // ✅ pass template
                    $paymentLink
                )
            );
    }
}