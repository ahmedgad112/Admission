<?php

namespace App\Http\Controllers;

use App\Support\AppLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', AppLocale::available())],
        ]);

        $locale = $validated['locale'];

        return back()->withCookie(cookie()->forever('locale', $locale));
    }
}
