@extends('admin.layouts.app')

@section('title', 'تعديل وسيط جامعي: ' . ($universityFacility->title_ar ?: 'وسيط #' . $universityFacility->id))

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-edit me-2"></i>تعديل وسيط جامعي: {{ $universityFacility->title_ar ?: 'وسيط #' . $universityFacility->id }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.university-facilities.update', $universityFacility) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_ar" class="form-label">العنوان (عربي) (اختياري)</label>
                        <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar', $universityFacility->title_ar) }}">
                        @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">العنوان (إنجليزي) (اختياري)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $universityFacility->title_en) }}">
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="media_file" class="form-label">تغيير ملف الوسائط (اختياري)</label>
                    <input type="file" class="form-control @error('media_file') is-invalid @enderror" id="media_file" name="media_file">
                    <small class="form-text text-muted">الأنواع المدعومة: صور، فيديو، مستندات. الحد الأقصى: 50MB.</small>
                    @error('media_file') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    @if($universityFacility->file_url)
                        <div class="mt-2">
                            <p>الملف الحالي:
                                <a href="{{ Storage::url($universityFacility->file_url) }}" target="_blank">
                                    {{ basename($universityFacility->file_url) }}
                                </a>
                            </p>
                            @if(Str::startsWith(Storage::mimeType($universityFacility->file_url), 'image/'))
                                <img src="{{ Storage::url($universityFacility->file_url) }}" alt="معاينة" style="max-width: 200px; max-height: 150px; object-fit: contain;">
                            @endif
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_media_file" id="remove_media_file" value="1">
                                <label class="form-check-label" for="remove_media_file">
                                    إزالة الملف الحالي (إذا تم رفع ملف جديد، سيتم استبداله تلقائياً)
                                </label>
                            </div>
                        </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="media_type" class="form-label">نوع الوسيط <span class="text-danger">*</span></label>
                        <select class="form-select @error('media_type') is-invalid @enderror" id="media_type" name="media_type" required>
                            <option value="">-- اختر النوع --</option>
                            @foreach($mediaTypes as $typeKey => $typeName)
                                <option value="{{ $typeKey }}" {{ old('media_type', $universityFacility->media_type) == $typeKey ? 'selected' : '' }}>{{ $typeName }}</option>
                            @endforeach
                        </select>
                        @error('media_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">التصنيف (اختياري)</label>
                        <input list="category-suggestions" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $universityFacility->category) }}">
                         <datalist id="category-suggestions">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="faculty_id" class="form-label">الكلية المرتبطة (اختياري)</label>
                    <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id" name="faculty_id">
                        <option value="">-- لا يوجد ارتباط بكلية --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $universityFacility->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('faculty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي) (اختياري)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $universityFacility->description_ar) }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي) (اختياري)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $universityFacility->description_en) }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث الوسيط</button>
                    <a href="{{ route('admin.university-facilities.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection