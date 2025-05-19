@extends('admin.layouts.app')

@section('title', 'إرسال تنبيه جديد')

@push('styles')
{{-- يمكنك إضافة مكتبات لتحسين اختيار الطلاب المتعددين إذا أردت مثل Select2 --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
@endpush

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-paper-plane me-2"></i>إرسال تنبيه جديد</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.notifications.store') }}" method="POST">
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
                    <label for="body_ar" class="form-label">نص التنبيه (عربي) <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('body_ar') is-invalid @enderror" id="body_ar" name="body_ar" rows="5" required>{{ old('body_ar') }}</textarea>
                    @error('body_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="body_en" class="form-label">نص التنبيه (إنجليزي)</label>
                    <textarea class="form-control @error('body_en') is-invalid @enderror" id="body_en" name="body_en" rows="5">{{ old('body_en') }}</textarea>
                    @error('body_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">نوع التنبيه <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', 'general') }}" placeholder="مثال: general, course_update, event_reminder" required>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="target_audience_type" class="form-label">الجمهور المستهدف <span class="text-danger">*</span></label>
                        <select class="form-select @error('target_audience_type') is-invalid @enderror" id="target_audience_type" name="target_audience_type" required onchange="toggleTargetOptions()">
                            <option value="all" {{ old('target_audience_type', 'all') == 'all' ? 'selected' : '' }}>جميع الطلاب</option>
                            <option value="course_specific" {{ old('target_audience_type') == 'course_specific' ? 'selected' : '' }}>طلاب مقرر معين</option>
                            <option value="custom_group" {{ old('target_audience_type') == 'custom_group' ? 'selected' : '' }}>مجموعة طلاب مخصصة</option>
                            <option value="individual" {{ old('target_audience_type') == 'individual' ? 'selected' : '' }}>طالب معين</option>
                        </select>
                        @error('target_audience_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div id="related_course_div" class="mb-3" style="display: {{ old('target_audience_type') == 'course_specific' ? 'block' : 'none' }};">
                    <label for="related_course_id" class="form-label">المقرر المرتبط (إذا كان الجمهور طلاب مقرر)</label>
                    <select class="form-select @error('related_course_id') is-invalid @enderror" id="related_course_id" name="related_course_id">
                        <option value="">-- اختر المقرر --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('related_course_id') == $course->id ? 'selected' : '' }}>{{ $course->name_ar }} ({{ $course->code }})</option>
                        @endforeach
                    </select>
                    @error('related_course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div id="related_event_div" class="mb-3"> {{-- يمكن ربط التنبيه بفعالية بغض النظر عن الجمهور --}}
                    <label for="related_event_id" class="form-label">الفعالية المرتبطة (اختياري)</label>
                    <select class="form-select @error('related_event_id') is-invalid @enderror" id="related_event_id" name="related_event_id">
                        <option value="">-- اختر الفعالية --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('related_event_id') == $event->id ? 'selected' : '' }}>{{ $event->title_ar }}</option>
                        @endforeach
                    </select>
                    @error('related_event_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- هذا هو الحقل الذي سنقوم بملئه بالطلاب --}}
                <div id="students_div" class="mb-3" style="display: {{ in_array(old('target_audience_type'), ['custom_group', 'individual']) ? 'block' : 'none' }};">
                    <label for="student_ids" class="form-label">الطلاب المستهدفون (إذا كان الجمهور مخصصًا/فرديًا) <span id="student_ids_required" class="text-danger">*</span></label>
                    <select class="form-select @error('student_ids') is-invalid @enderror @error('student_ids.*') is-invalid @enderror" id="student_ids" name="student_ids[]" multiple data-placeholder="ابحث عن الطلاب...">
                        <option value="">-- اختر طالباً واحداً أو أكثر --</option> {{-- هذه الخيار لا يظهر في multiple select --}}

                        {{-- <<-- قم بإلغاء التعليق عن هذه الحلقة --}}
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', [])) ? 'selected' : '' }}>
                                {{ $student->full_name_ar }} ({{ $student->student_university_id }})
                            </option>
                        @endforeach
                         {{-- <<-- نهاية الحلقة --}}

                    </select>
                    <small class="form-text text-muted">يمكنك اختيار طالب واحد أو أكثر. للحصول على تجربة أفضل مع عدد كبير من الطلاب، استخدم Select2 مع بحث.</small>
                    @error('student_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('student_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="publish_datetime" class="form-label">تاريخ ووقت النشر <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('publish_datetime') is-invalid @enderror" id="publish_datetime" name="publish_datetime" value="{{ old('publish_datetime', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('publish_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expiry_datetime" class="form-label">تاريخ ووقت انتهاء الصلاحية (اختياري)</label>
                        <input type="datetime-local" class="form-control @error('expiry_datetime') is-invalid @enderror" id="expiry_datetime" name="expiry_datetime" value="{{ old('expiry_datetime') }}">
                        @error('expiry_datetime') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> إرسال/جدولة التنبيه</button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script>
    function toggleTargetOptions() {
        const targetTypeSelect = document.getElementById('target_audience_type');
        const targetType = targetTypeSelect.value;
        const courseDiv = document.getElementById('related_course_div');
        const studentsDiv = document.getElementById('students_div');
        const studentsSelect = document.getElementById('student_ids');
        const studentsRequiredSpan = document.getElementById('student_ids_required');

        courseDiv.style.display = (targetType === 'course_specific') ? 'block' : 'none';
        studentsDiv.style.display = (targetType === 'custom_group' || targetType === 'individual') ? 'block' : 'none';

        // إضافة/إزالة required بناءً على نوع الجمهور المستهدف
        if (targetType === 'custom_group' || targetType === 'individual') {
             studentsSelect.setAttribute('required', 'required');
             studentsRequiredSpan.style.display = 'inline';
        } else {
             studentsSelect.removeAttribute('required');
             studentsRequiredSpan.style.display = 'none';
        }


        if (targetType !== 'course_specific') {
            document.getElementById('related_course_id').value = '';
        }
        // هنا نحتاج إلى مسح اختيارات الطلاب إذا تم التغيير من custom/individual إلى شيء آخر
        if (targetType !== 'custom_group' && targetType !== 'individual') {
             // إذا كنت تستخدم Select2، استخدم هذا:
             // $('#student_ids').val(null).trigger('change');
             // لحقل select عادي، قم بمسح الاختيارات يدوياً:
              for (let i = 0; i < studentsSelect.options.length; i++) {
                 studentsSelect.options[i].selected = false;
             }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleTargetOptions(); // استدعاء عند تحميل الصفحة
        // $('#student_ids').select2({ // مثال لتفعيل Select2 إذا استخدمته
        //     placeholder: 'ابحث عن الطلاب بالاسم أو الرقم الجامعي',
        //     // allowClear: true, // للسماح بمسح الاختيار (مع Select2)
        //     // ajax: { ... } // يمكنك إضافة AJAX هنا للبحث الديناميكي مع Select2
        // });
    });
</script>
@endpush