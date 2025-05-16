<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentProfileController extends Controller
{
    /**
     * عرض ملف الطالب المسجل دخوله.
     */
    public function show(Request $request)
    {
        return response()->json(Auth::user());
    }

    /**
     * تحديث بيانات ملف الطالب (مثل الاسم، الصورة الشخصية).
     */
    public function update(Request $request)
    {
        $student = Auth::user();

        $validatedData = $request->validate([
            'full_name_ar' => 'sometimes|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            // 'profile_picture_url' => 'nullable|url', // أو 'image' إذا كنت سترفع صورة
            // أضف حقول أخرى قابلة للتحديث
        ]);

        $student->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'student' => $student,
        ]);
    }

    /**
     * تغيير كلمة مرور الطالب.
     */
    public function changePassword(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($student) {
                if (!Hash::check($value, $student->password_hash)) {
                    $fail(trans('auth.password')); // رسالة خطأ كلمة المرور الحالية غير صحيحة
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()], // قواعد كلمة المرور الجديدة
        ]);

        $student->password_hash = Hash::make($request->password);
        $student->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }
}