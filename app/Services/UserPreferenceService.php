<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserPreferenceService
{
    public function getPreferences(User $user): Collection
    {
        return $user->preferences()->get();
    }

    public function savePreferences(User $user, array $preferences): void
    {
        foreach ($preferences as $pref) {
            $user->preferences()->updateOrCreate(
                ['type' => $pref['type'], 'value' => $pref['value']],
                []
            );
        }
    }
}
