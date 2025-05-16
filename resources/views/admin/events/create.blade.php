@extends('admin.layouts.app')

@section('title', 'إضافة فعالية جديدة')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const registrationDeadlineInput = document.getElementById('registration_deadline');
        const eventStartDateInput = document.getElementById('event_start_datetime');

        if (eventStartDateInput && registrationDeadlineInput) {
            eventStartDateInput.addEventListener('change', function() {
                registrationDeadlineInput.max = this.value;
            });
        }
    });
</script>
@endpush

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-plus-circle me-2"></i>إضافة فعالية جديدة</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_ar" class="form-label">العنوان (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">العنوان (إنجليزي)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}">
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي) <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="4" required>{{ old('description_ar') }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4">{{ old('description_en') }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_start_datetime" class="form-label">تاريخ ووقت البدء <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('event_start_datetime') is-invalid @enderror" id="event_start_datetime" name="event_start_datetime" value="{{ old('event_start_datetime') }}" required>
                        @error('event_start_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="event_end_datetime" class="form-label">تاريخ ووقت الانتهاء (اختياري)</label>
                        <input type="datetime-local" class="form-control @error('event_end_datetime') is-invalid @enderror" id="event_end_datetime" name="event_end_datetime" value="{{ old('event_end_datetime') }}">
                        @error('event_end_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location_text" class="form-label">الموقع (نص)</label>
                        <input type="text" class="form-control @error('location_text') is-invalid @enderror" id="location_text" name="location_text" value="{{ old('location_text') }}">
                        @error('location_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">التصنيف</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="مثال: ندوة، ورشة عمل">
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="main_image" class="form-label">الصورة الرئيسية للفعالية (اختياري)</label>
                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image">
                    @error('main_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="requires_registration" class="form-label">يتطلب تسجيل؟ <span class="text-danger">*</span></label>
                        <select class="form-select @error('requires_registration') is-invalid @enderror" id="requires_registration" name="requires_registration" required>
                            <option value="1" {{ old('requires_registration', '0') == '1' ? 'selected' : '' }}>نعم</option>
                            <option value="0" {{ old('requires_registration', '0') == '0' ? 'selected' : '' }}>لا</option>
                        </select>
                        @error('requires_registration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="registration_deadline" class="form-label">الموعد النهائي للتسجيل (إذا كان مطلوباً)</label>
                        <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}">
                        @error('registration_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="max_attendees" class="form-label">الحد الأقصى للحضور (اختياري)</label>
                    <input type="number" class="form-control @error('max_attendees') is-invalid @enderror" id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}" min="0">
                    @error('max_attendees') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="organizer_info" class="form-label">معلومات الجهة المنظمة (اختياري)</label>
                    <input type="text" class="form-control @error('organizer_info') is-invalid @enderror" id="organizer_info" name="organizer_info" value="{{ old('organizer_info') }}">
                    @error('organizer_info') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="organizing_faculty_id" class="form-label">الكلية المنظمة (اختياري)</label>
                    <select class="form-select @error('organizing_faculty_id') is-invalid @enderror" id="organizing_faculty_id" name="organizing_faculty_id">
                        <option value="">-- لا يوجد --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('organizing_faculty_id') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('organizing_faculty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        @foreach($statuses as $statusKey => $statusName)
                            <option value="{{ $statusKey }}" {{ old('status', 'draft') == $statusKey ? 'selected' : '' }}>{{ $statusName }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ الفعالية</button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection