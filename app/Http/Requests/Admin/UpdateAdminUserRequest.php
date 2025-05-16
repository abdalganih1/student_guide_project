<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check() && Auth::guard('admin_web')->user()->role === 'superadmin';
    }

    public function rules(): array
    {
        $adminUserId = $this->route('admin_user')->id; // اسم البارامتر في المسار

        return [
            'username' => ['required', 'string', 'max:100', Rule::unique('admin_users', 'username')->ignore($adminUserId)],
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin_users', 'email')->ignore($adminUserId)],
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'role' => 'required|string|in:admin,content_manager,superadmin',
            'is_active' => 'required|boolean',
        ];
    }
}