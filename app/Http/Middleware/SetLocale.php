<?php

namespace App\Http\Middleware;

use App\Support\AppLocale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale');

        if (! AppLocale::isSupported(is_string($locale) ? $locale : null)) {
            $locale = config('app.locale');
        }

        if (! is_string($locale) || ! AppLocale::isSupported($locale)) {
            $locale = AppLocale::English;
        }

        App::setLocale($locale);
        View::share('locale', $locale);
        View::share('dir', AppLocale::direction($locale));

        return $next($request);
    }
}
