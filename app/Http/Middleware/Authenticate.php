<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('login');
    // }
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            if ($request->routeIs('admin.*')) { // تحقق مما إذا كان المسار يخص المدير
                return route('admin.login.form');
            }
            return route('login'); // صفحة تسجيل دخول الطلاب الافتراضية
        }
        return null;
    }
}
