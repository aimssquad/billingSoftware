<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\OrganizationMailSetting;

class TenantMailConfigService
{
    public static function configure(int $organizationId): void
    {
        $mailSetting = OrganizationMailSetting::where('organization_id', $organizationId)
            ->where('is_active', true)
            ->first();

        if (!$mailSetting) {
            throw new \Exception("Mail settings not configured for this organization.");
        }

        Config::set('mail.default', 'tenant');

        Config::set('mail.mailers.tenant', [
            'transport' => $mailSetting->driver,
            'host' => $mailSetting->host,
            'port' => $mailSetting->port,
            'encryption' => $mailSetting->encryption,
            'username' => $mailSetting->username,
            'password' => $mailSetting->password,
            'timeout' => null,
            'from' => [
                'address' => $mailSetting->from_address,
                'name' => $mailSetting->from_name,
            ],
        ]);

        // Config::set('mail.from.address', $mailSetting->from_address);
        // Config::set('mail.from.name', $mailSetting->from_name);
    }
}