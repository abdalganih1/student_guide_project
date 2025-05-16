@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الطالب: ' . $student->full_name_ar)

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-user-edit me-2"></i>تعديل بيانات الطالب: {{ $student->full_name_ar }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_university_id" class="form-label">الرقم الجامعي <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('student_university_id') is-invalid @enderror" id="student_university_id" name="student_university_id" value="{{ old('student_university_id', $student->student_university_id) }}" required>
                        @error('student_university_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="enrollment_year" class="form-label">سنة الالتحاق</label>
                        <input type="number" class="form-control @error('enrollment_year') is-invalid @enderror" id="enrollment_year" name="enrollment_year" value="{{ old('enrollment_year', $student->enrollment_year) }}" min="1980" max="{{ date('Y') + 1 }}">
                        @error('enrollment_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name_ar" class="form-label">الاسم الكامل (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('full_name_ar') is-invalid @enderror" id="full_name_ar" name="full_name_ar" value="{{ old('full_name_ar', $student->full_name_ar) }}" required>
                        @error('full_name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="full_name_en" class="form-label">الاسم الكامل (إنجليزي)</label>
                        <input type="text" class="form-control @error('full_name_en') is-invalid @enderror" id="full_name_en" name="full_name_en" value="{{ old('full_name_en', $student->full_name_en) }}">
                        @error('full_name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $student->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور الجديدة (اتركه فارغًا لعدم التغيير)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="specialization_id" class="form-label">الاختصاص (اختياري)</label>
                    <select class="form-select @error('specialization_id') is-invalid @enderror" id="specialization_id" name="specialization_id">
                        <option value="">-- اختر الاختصاص --</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" {{ old('specialization_id', $student->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                {{ $specialization->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">الصورة الشخصية (اختياري)</label>
                    <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture">
                    @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($student->profile_picture_url)
                        <div class="mt-2">
                            <img src="{{ Storage::url($student->profile_picture_url) }}" alt="الصورة الحالية" style="width: 100px; height: 100px; object-fit: cover; border:1px solid #ddd; padding:3px;">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_profile_picture" id="remove_profile_picture" value="1">
                                <label class="form-check-label" for="remove_profile_picture">
                                    إزالة الصورة الحالية (إذا تم رفع صورة جديدة، سيتم استبدالها تلقائياً)
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="is_active" class="form-label">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                        <option value="1" {{ old('is_active', $student->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ old('is_active', $student->is_active) == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث بيانات الطالب</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection