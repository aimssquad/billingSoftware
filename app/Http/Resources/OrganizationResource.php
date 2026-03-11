<?php

namespace App\Http\Resources;

use App\Models\CountryField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    // public function toArray(Request $request): array
    // {
    //     return [
    //         'id' => $this->id,
    //         'organization_code' => $this->organization_code,
    //         'company_name' => $this->company_name,
    //         'legal_name' => $this->legal_name,
    //         'email' => $this->email,
    //         'phone' => $this->phone,
    //         'gstin' => $this->gstin,
    //         'address' => $this->address,
    //         'country' => $this->country,
    //         'status' => $this->status,
    //         'created_at' => $this->created_at?->toIso8601String(),
    //     ];
    // }

      public function toArray(Request $request): array
    {
        // Fetch master fields for this country
        $countryFields = CountryField::where('country', $this->country)
            ->where('is_active', true)
            ->get()
            ->keyBy('field_key');

        // Map organization stored values
        $dynamicFields = [];

        foreach ($countryFields as $key => $master) {

            $detail = $this->countryDetails->firstWhere('field_key', $key);

            $dynamicFields[] = [
                'field_key'   => $key,
                'field_label' => $master->field_label,
                'field_type'  => $master->field_type,
                'is_required' => $master->is_required,
                'value'       => $detail->field_value ?? null,
            ];
        }

        return [
            'id' => $this->id,
            'organization_code' => $this->organization_code,
            'company_name' => $this->company_name,
            'legal_name' => $this->legal_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gstin' => $this->gstin,
            'address' => $this->address,
            'country' => $this->country,
            'status' => $this->status,

            // 👇 Add this
            'country_dynamic_fields' => $dynamicFields,

            'active_subscription' => $this->whenLoaded('activeSubscription'),
            'settings' => $this->whenLoaded('settings'),

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
