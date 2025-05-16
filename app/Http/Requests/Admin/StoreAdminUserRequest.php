<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check() && Auth::guard('admin_web')->user()->role === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:100|unique:admin_users,username',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users,email',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'role' => 'required|string|in:admin,content_manager,superadmin',
            'is_active' => 'required|boolean',
        ];
    }
}