<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

test('two factor challenge redirects to login when not authenticated', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $this->get(route('two-factor.login'))
        ->assertRedirect(route('login'));
});

test('two factor challenge can be rendered', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->withTwoFactor()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->get(route('two-factor.login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/TwoFactorChallenge'),
        );
});

test('passkey login options are available to guests', function () {
    $this->skipUnlessFortifyHas(Features::passkeys());

    $this->get(route('passkey.login-options'))
        ->assertOk();
});
