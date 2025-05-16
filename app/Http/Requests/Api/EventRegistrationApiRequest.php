<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // لاستخدامه في authorize

class EventRegistrationApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // تأكد من أن المستخدم المسجل (الطالب) هو المصرح له بإجراء هذا الطلب
        return Auth::guard('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // الحقول التي قد يرسلها تطبيق الموبايل عند التسجيل في فعالية
            // حاليًا، المتحكم لا يتوقع أي حقول إضافية إلزامية،
            // لكن يمكنك إضافتها هنا إذا تغيرت المتطلبات.

            // مثال: إذا كان هناك حقل "الدافع للحضور"
            'motivation' => 'nullable|string|max:1000',

            // مثال: إذا كان هناك أسئلة إضافية خاصة بالفعالية
            // 'answers.question_1' => 'nullable|string|max:255',
            // 'answers.question_2' => 'nullable|boolean',
            // 'answers' => 'nullable|array', // إذا كانت الإجابات مجموعة
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'motivation.string' => 'حقل الدافع يجب أن يكون نصًا.',
            'motivation.max' => 'حقل الدافع يجب ألا يتجاوز 1000 حرف.',
            // رسائل خطأ مخصصة للحقول الإضافية
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'motivation' => 'الدافع للحضور',
            // أسماء مخصصة للحقول الإضافية
        ];
    }
}