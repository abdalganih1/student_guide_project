@extends('admin.layouts.app')

@section('title', 'تعديل المقرر: ' . $course->name_ar)

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-edit me-2"></i>تعديل المقرر: {{ $course->name_ar }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $course->name_ar) }}" required>
                        @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $course->name_en) }}">
                        @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">رمز المقرر <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $course->code) }}" required>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="specialization_id" class="form-label">الاختصاص <span class="text-danger">*</span></label>
                        <select class="form-select @error('specialization_id') is-invalid @enderror" id="specialization_id" name="specialization_id" required>
                            <option value="">-- اختر الاختصاص --</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ old('specialization_id', $course->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $course->description_ar) }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $course->description_en) }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="semester_display_info" class="form-label">معلومات عرض الفصل <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('semester_display_info') is-invalid @enderror" id="semester_display_info" name="semester_display_info" value="{{ old('semester_display_info', $course->semester_display_info) }}" required>
                        @error('semester_display_info') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="year_level" class="form-label">مستوى السنة (اختياري)</label>
                        <input type="number" class="form-control @error('year_level') is-invalid @enderror" id="year_level" name="year_level" value="{{ old('year_level', $course->year_level) }}" min="1">
                        @error('year_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="credits" class="form-label">الساعات المعتمدة (اختياري)</label>
                        <input type="number" class="form-control @error('credits') is-invalid @enderror" id="credits" name="credits" value="{{ old('credits', $course->credits) }}" min="0">
                        @error('credits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="is_enrollable" class="form-label">إمكانية التسجيل <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_enrollable') is-invalid @enderror" id="is_enrollable" name="is_enrollable" required>
                            <option value="1" {{ old('is_enrollable', $course->is_enrollable) == '1' ? 'selected' : '' }}>نعم، متاح للتسجيل</option>
                            <option value="0" {{ old('is_enrollable', $course->is_enrollable) == '0' ? 'selected' : '' }}>لا، غير متاح للتسجيل</option>
                        </select>
                        @error('is_enrollable') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="enrollment_capacity" class="form-label">سعة التسجيل (اختياري)</label>
                        <input type="number" class="form-control @error('enrollment_capacity') is-invalid @enderror" id="enrollment_capacity" name="enrollment_capacity" value="{{ old('enrollment_capacity', $course->enrollment_capacity) }}" min="0">
                        @error('enrollment_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث المقرر</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection