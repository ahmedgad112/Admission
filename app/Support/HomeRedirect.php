<?php

namespace App\Support;

use App\Enums\HomePage;
use App\Models\User;

class HomeRedirect
{
    public function url(?User $user): string
    {
        if ($user === null) {
            return route('login', absolute: false);
        }

        $user->loadMissing('role');
        $preferred = $user->role?->homePage() ?? HomePage::Dashboard;

        if ($preferred->isAllowedFor($user)) {
            return route($preferred->routeName(), absolute: false);
        }

        foreach (HomePage::cases() as $page) {
            if ($page->isAllowedFor($user)) {
                return route($page->routeName(), absolute: false);
            }
        }

        return route('profile.edit', absolute: false);
    }
}
