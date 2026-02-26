<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\OrganizationMailSetting;
use App\Mail\InvoiceSentMail;

class TenantMailService
{
    public static function send($organizationId, $invoice, $to)
    {
        $mailSetting = OrganizationMailSetting::where('organization_id', $organizationId)
            ->where('is_active', true)
            ->first();

        TenantMailConfigService::configure($organizationId);

        Mail::mailer('tenant')
            ->to($to)
            ->send(
                new InvoiceSentMail(
                    $invoice,
                    config('app.frontend_url'),
                    $mailSetting->from_address,
                    $mailSetting->from_name
                )
            );
    }
}