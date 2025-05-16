<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student; // أو النموذج الذي يمثل المستخدم (الطالب)
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * تسجيل دخول الطالب.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email', // أو 'student_university_id' => 'required|string'
            'password' => 'required|string',
        ]);

        // ابحث عن الطالب بواسطة البريد الإلكتروني أو الرقم الجامعي
        $student = Student::where('email', $request->email)->first();
        // أو $student = Student::where('student_university_id', $request->student_university_id)->first();

        if (!$student || !Hash::check($request->password, $student->password_hash)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')], // رسالة خطأ عامة
            ]);
        }

        // حذف أي توكن قديم وإنشاء توكن جديد
        $student->tokens()->delete();
        $token = $student->createToken('student-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'student' => $student, // يمكنك اختيار البيانات التي تريد إرجاعها
            'token' => $token,
        ]);
    }

    /**
     * تسجيل خروج الطالب.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // حذف التوكن الحالي المستخدم

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * (اختياري) جلب بيانات الطالب المسجل دخوله حاليًا.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}