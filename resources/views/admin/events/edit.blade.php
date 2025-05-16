@extends('admin.layouts.app')

@section('title', 'تعديل الفعالية: ' . $event->title_ar)

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const registrationDeadlineInput = document.getElementById('registration_deadline');
        const eventStartDateInput = document.getElementById('event_start_datetime');

        function setMaxDeadline() {
            if (eventStartDateInput.value) {
                registrationDeadlineInput.max = eventStartDateInput.value;
            }
        }

        if (eventStartDateInput && registrationDeadlineInput) {
            eventStartDateInput.addEventListener('change', setMaxDeadline);
            setMaxDeadline(); // Set on page load
        }
    });
</script>
@endpush

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-edit me-2"></i>تعديل الفعالية: {{ $event->title_ar }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- نفس حقول نموذج الإنشاء، ولكن مع ملء القيم من $event --}}
                {{-- مثال لحقل العنوان --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_ar" class="form-label">العنوان (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar', $event->title_ar) }}" required>
                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">العنوان (إنجليزي)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $event->title_en) }}">
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي) <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="4" required>{{ old('description_ar', $event->description_ar) }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="4">{{ old('description_en', $event->description_en) }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="event_start_datetime" class="form-label">تاريخ ووقت البدء <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('event_start_datetime') is-invalid @enderror" id="event_start_datetime" name="event_start_datetime" value="{{ old('event_start_datetime', $event->event_start_datetime ? $event->event_start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                        @error('event_start_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="event_end_datetime" class="form-label">تاريخ ووقت الانتهاء (اختياري)</label>
                        <input type="datetime-local" class="form-control @error('event_end_datetime') is-invalid @enderror" id="event_end_datetime" name="event_end_datetime" value="{{ old('event_end_datetime', $event->event_end_datetime ? $event->event_end_datetime->format('Y-m-d\TH:i') : '') }}">
                        @error('event_end_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location_text" class="form-label">الموقع (نص)</label>
                        <input type="text" class="form-control @error('location_text') is-invalid @enderror" id="location_text" name="location_text" value="{{ old('location_text', $event->location_text) }}">
                        @error('location_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">التصنيف</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $event->category) }}">
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="main_image" class="form-label">الصورة الرئيسية للفعالية (اختياري)</label>
                    <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image">
                    @error('main_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($event->main_image_url)
                        <div class="mt-2">
                            <img src="{{ Storage::url($event->main_image_url) }}" alt="الصورة الحالية" style="max-width: 200px; max-height: 150px; object-fit: contain; border:1px solid #ddd; padding:5px;">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_main_image" id="remove_main_image" value="1">
                                <label class="form-check-label" for="remove_main_image">
                                    إزالة الصورة الحالية (إذا تم رفع صورة جديدة، سيتم استبدالها تلقائياً)
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="requires_registration" class="form-label">يتطلب تسجيل؟ <span class="text-danger">*</span></label>
                        <select class="form-select @error('requires_registration') is-invalid @enderror" id="requires_registration" name="requires_registration" required>
                            <option value="1" {{ old('requires_registration', $event->requires_registration) == '1' ? 'selected' : '' }}>نعم</option>
                            <option value="0" {{ old('requires_registration', $event->requires_registration) == '0' ? 'selected' : '' }}>لا</option>
                        </select>
                        @error('requires_registration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="registration_deadline" class="form-label">الموعد النهائي للتسجيل (إذا كان مطلوباً)</label>
                        <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('registration_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="max_attendees" class="form-label">الحد الأقصى للحضور (اختياري)</label>
                    <input type="number" class="form-control @error('max_attendees') is-invalid @enderror" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}" min="0">
                    @error('max_attendees') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="organizer_info" class="form-label">معلومات الجهة المنظمة (اختياري)</label>
                    <input type="text" class="form-control @error('organizer_info') is-invalid @enderror" id="organizer_info" name="organizer_info" value="{{ old('organizer_info', $event->organizer_info) }}">
                    @error('organizer_info') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="organizing_faculty_id" class="form-label">الكلية المنظمة (اختياري)</label>
                    <select class="form-select @error('organizing_faculty_id') is-invalid @enderror" id="organizing_faculty_id" name="organizing_faculty_id">
                        <option value="">-- لا يوجد --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('organizing_faculty_id', $event->organizing_faculty_id) == $faculty->id ? 'selected' : '' }}>
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
                            <option value="{{ $statusKey }}" {{ old('status', $event->status) == $statusKey ? 'selected' : '' }}>{{ $statusName }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث الفعالية</button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection