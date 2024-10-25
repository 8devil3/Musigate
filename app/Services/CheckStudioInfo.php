<?php

namespace App\Services;

use App\Models\Studio\Studio;

class CheckStudioInfo
{
    public static function check(Studio $studio): bool
    {
        return $studio->name
        && ($studio->rooms()->where('is_published', true)->count() > 0 || $studio->bundles()->where('is_published', true)->count() > 0)
        && ($studio->category === 'Professional' ? $studio->vat : true)
        && $studio->availability()->where('open_type', 'close')->count() < 7
        && $studio->location->complete_address
        && $studio->payment_methods()->count() > 0
        && $studio->photos()->count() >= 3
        && strlen($studio->description) > 100
        && ($studio->contacts->email || $studio->contacts->phone || $studio->contacts->telegram || $studio->contacts->messenger || $studio->contacts->whatsapp);
    }

    public static function update_studio(Studio $studio): void
    {
        $studio->update([
            'is_complete' => false,
            'is_published' => false,
        ]);

        if(self::check($studio)){
            $studio->update([
                'is_complete' => true,
                'is_published' => true,
            ]);
        }
    }
}
