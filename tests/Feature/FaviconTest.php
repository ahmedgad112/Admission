<?php

test('the home page includes the favicon links', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="/favicon.ico?v=2"', false)
        ->assertSee('href="/favicon.svg?v=2"', false)
        ->assertSee('href="/apple-touch-icon.png?v=2"', false);
});

test('favicon files exist in public', function () {
    expect(public_path('favicon.svg'))->toBeFile()
        ->and(public_path('favicon.ico'))->toBeFile()
        ->and(public_path('apple-touch-icon.png'))->toBeFile();

    expect(file_get_contents(public_path('favicon.svg')))
        ->toContain('aria-label="'.config('app.name').'"')
        ->toContain('#1A8A78');
});
