@extends('admin.layouts.app')

@section('title', 'تعديل مشروع التخرج: ' . Str::limit($project->title_ar, 30))

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-edit me-2"></i>تعديل مشروع التخرج: {{ Str::limit($project->title_ar, 50) }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title_ar" class="form-label">عنوان المشروع (عربي) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar', $project->title_ar) }}" required>
                    @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="title_en" class="form-label">عنوان المشروع (إنجليزي)</label>
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $project->title_en) }}">
                    @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="specialization_ids" class="form-label">الاختصاصات المرتبطة <span class="text-danger">*</span></label>
                        <select class="form-select @error('specialization_ids') is-invalid @enderror @error('specialization_ids.*') is-invalid @enderror" id="specialization_ids" name="specialization_ids[]" multiple required>
                            <option value="">-- اختر اختصاصاً واحداً أو أكثر --</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ in_array($specialization->id, old('specialization_ids', $currentSpecializationIds)) ? 'selected' : '' }}>
                                    {{ $specialization->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @error('specialization_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="supervisor_instructor_id" class="form-label">المشرف (اختياري)</label>
                        <select class="form-select @error('supervisor_instructor_id') is-invalid @enderror" id="supervisor_instructor_id" name="supervisor_instructor_id">
                            <option value="">-- اختر المشرف --</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('supervisor_instructor_id', $project->supervisor_instructor_id) == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_instructor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="year" class="form-label">السنة <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $project->year) }}" required min="2000" max="{{ date('Y') + 5 }}">
                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="semester" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                        <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                            <option value="">-- اختر الفصل --</option>
                            @foreach($semesters as $semester_option) {{-- تغيير اسم المتغير لتجنب التعارض --}}
                            <option value="{{ $semester_option }}" {{ old('semester', $project->semester) == $semester_option ? 'selected' : '' }}>{{ $semester_option }}</option>
                            @endforeach
                        </select>
                        @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="student_names" class="form-label">أسماء الطلاب (اختياري - افصل بينهم بفاصلة)</label>
                    <input type="text" class="form-control @error('student_names') is-invalid @enderror" id="student_names" name="student_names" value="{{ old('student_names', $project->student_names) }}">
                    @error('student_names') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="project_type" class="form-label">نوع المشروع (اختياري)</label>
                    <input type="text" class="form-control @error('project_type') is-invalid @enderror" id="project_type" name="project_type" value="{{ old('project_type', $project->project_type) }}">
                    @error('project_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="abstract_ar" class="form-label">الملخص (عربي) (اختياري)</label>
                    <textarea class="form-control @error('abstract_ar') is-invalid @enderror" id="abstract_ar" name="abstract_ar" rows="4">{{ old('abstract_ar', $project->abstract_ar) }}</textarea>
                    @error('abstract_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="abstract_en" class="form-label">الملخص (إنجليزي) (اختياري)</label>
                    <textarea class="form-control @error('abstract_en') is-invalid @enderror" id="abstract_en" name="abstract_en" rows="4">{{ old('abstract_en', $project->abstract_en) }}</textarea>
                    @error('abstract_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                 <div class="mb-3">
                    <label for="keywords" class="form-label">الكلمات المفتاحية (اختياري - افصل بينهم بفاصلة)</label>
                    <input type="text" class="form-control @error('keywords') is-invalid @enderror" id="keywords" name="keywords" value="{{ old('keywords', $project->keywords) }}">
                    @error('keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث المشروع</button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection