@extends('admin.layouts.app')

@section('title', 'تفاصيل المقرر: ' . $course->name_ar)

@push('scripts')
<script>
    function confirmResourceDeletion(formId) {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذا المورد؟')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-book-reader me-2"></i>تفاصيل المقرر: {{ $course->name_ar }} ({{ $course->code }})</h1>
        <div>
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل المقرر</a>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    {{-- معلومات المقرر الأساسية --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">معلومات المقرر</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>الاسم (عربي):</strong> {{ $course->name_ar }}</p>
                    <p><strong>الاسم (إنجليزي):</strong> {{ $course->name_en ?: '-' }}</p>
                    <p><strong>رمز المقرر:</strong> {{ $course->code }}</p>
                    <p><strong>الاختصاص:</strong> <a href="{{ route('admin.specializations.show', $course->specialization) }}">{{ $course->specialization->name_ar ?? 'غير محدد' }}</a></p>
                </div>
                <div class="col-md-6">
                    <p><strong>معلومات الفصل:</strong> {{ $course->semester_display_info }}</p>
                    <p><strong>مستوى السنة:</strong> {{ $course->year_level ?: '-' }}</p>
                    <p><strong>الساعات المعتمدة:</strong> {{ $course->credits ?: '-' }}</p>
                    <p><strong>متاح للتسجيل:</strong> {{ $course->is_enrollable ? 'نعم' : 'لا' }} @if($course->enrollment_capacity) (السعة: {{ $course->enrollment_capacity }}) @endif</p>
                </div>
            </div>
            @if($course->description_ar)
            <hr>
            <h6>الوصف (عربي):</h6>
            <p>{!! nl2br(e($course->description_ar)) !!}</p>
            @endif
            @if($course->description_en)
            <hr>
            <h6>الوصف (إنجليزي):</h6>
            <p>{!! nl2br(e($course->description_en)) !!}</p>
            @endif
            <hr>
            <small class="text-muted">
                تم إنشاؤه بواسطة: {{ $course->createdByAdmin->name_ar ?? 'غير معروف' }} في {{ $course->created_at->translatedFormat('Y-m-d') }} <br>
                آخر تحديث بواسطة: {{ $course->lastUpdatedByAdmin->name_ar ?? 'غير معروف' }} في {{ $course->updated_at->translatedFormat('Y-m-d') }}
            </small>
        </div>
    </div>

    {{-- إدارة موارد المقرر --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>موارد المقرر ({{ $course->resources->count() }})</h5>
        </div>
        <div class="card-body">
            @if($course->resources->isEmpty())
                <p class="text-muted">لا توجد موارد مضافة لهذا المقرر حالياً.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($course->resources as $resource)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ $resource->url }}" target="_blank">{{ $resource->title_ar }}</a> ({{ $resource->type }})
                            <small class="d-block text-muted">{{ $resource->description ?: '' }} - مضاف في: {{ $resource->semester_relevance ?: 'غير محدد' }}</small>
                        </div>
                        <form id="delete-resource-{{$resource->id}}" action="{{ route('admin.courses.resources.remove', [$course, $resource]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmResourceDeletion('delete-resource-{{$resource->id}}')" class="btn btn-sm btn-outline-danger" title="حذف المورد"><i class="fas fa-trash"></i></button>
                        </form>
                    </li>
                    @endforeach
                </ul>
            @endif
            <hr>
            <h6>إضافة مورد جديد:</h6>
            <form action="{{ route('admin.courses.resources.add', $course) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="title_ar" class="form-control form-control-sm @error('title_ar', 'addResourceForm') is-invalid @enderror" placeholder="العنوان (عربي) *" value="{{ old('title_ar') }}" required>
                        @error('title_ar', 'addResourceForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="title_en" class="form-control form-control-sm @error('title_en', 'addResourceForm') is-invalid @enderror" placeholder="العنوان (إنجليزي)" value="{{ old('title_en') }}">
                        @error('title_en', 'addResourceForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12">
                        <input type="url" name="url" class="form-control form-control-sm @error('url', 'addResourceForm') is-invalid @enderror" placeholder="رابط المورد *" value="{{ old('url') }}" required>
                         @error('url', 'addResourceForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-4">
                        <input type="text" name="type" class="form-control form-control-sm @error('type', 'addResourceForm') is-invalid @enderror" placeholder="نوع المورد (lecture_pdf) *" value="{{ old('type') }}" required>
                        @error('type', 'addResourceForm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="semester_relevance" class="form-control form-control-sm" placeholder="الفصل الدراسي للمورد" value="{{ old('semester_relevance') }}">
                    </div>
                    <div class="col-md-12">
                        <textarea name="description" class="form-control form-control-sm" placeholder="وصف قصير (اختياري)" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-4">
                         <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i> إضافة المورد</button>
                    </div>
                </div>
            </form>
             @if ($errors->hasBag('addResourceForm')) {{-- تم تغيير الشرط ليتوافق مع Laravel --}}
                <div class="alert alert-danger mt-3">
                    <h6>أخطاء في نموذج إضافة المورد:</h6>
                    <ul>
                        @foreach ($errors->getBag('addResourceForm')->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- تم حذف قسم إدارة تعيين المدرسين بالكامل --}}

    {{-- يمكنك إضافة قسم لعرض الطلاب المسجلين في المقرر هنا إذا أردت --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>الطلاب المسجلون في المقرر ({{ $course->enrolledStudents->count() }})</h5>
        </div>
        <div class="card-body">
            @if($course->enrolledStudents->isEmpty())
                <p class="text-muted">لا يوجد طلاب مسجلون في هذا المقرر حالياً.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($course->enrolledStudents as $studentEnrollment)
                        <li class="list-group-item">
                            @if ($studentEnrollment->student) {{-- <<< التحقق هنا --}}
                                <a href="{{ route('admin.students.show', $studentEnrollment->student) }}">{{ $studentEnrollment->student->full_name_ar ?? 'طالب غير معروف' }}</a>
                                - الحالة: {{ $studentEnrollment->status }}
                                @if($studentEnrollment->grade) - الدرجة: {{ $studentEnrollment->grade }} @endif
                                (مسجل في: {{ $studentEnrollment->semester_enrolled }})
                            @else
                                <span class="text-danger">طالب محذوف (ID: {{ $studentEnrollment->student_id }})</span>
                                - الحالة: {{ $studentEnrollment->status }}
                                @if($studentEnrollment->grade) - الدرجة: {{ $studentEnrollment->grade }} @endif
                                (مسجل في: {{ $studentEnrollment->semester_enrolled }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection