@extends('admin.layouts.app')

@section('title', 'تعديل بيانات المدرس: ' . $instructor->name_ar)

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-user-edit me-2"></i>تعديل بيانات المدرس: {{ $instructor->name_ar }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.instructors.update', $instructor) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $instructor->name_ar) }}" required>
                        @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $instructor->name_en) }}">
                        @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $instructor->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">اللقب العلمي/المهني</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $instructor->title) }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="faculty_id" class="form-label">الكلية (اختياري)</label>
                    <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id" name="faculty_id">
                        <option value="">-- اختر الكلية --</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $instructor->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('faculty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="office_location" class="form-label">موقع المكتب</label>
                    <input type="text" class="form-control @error('office_location') is-invalid @enderror" id="office_location" name="office_location" value="{{ old('office_location', $instructor->office_location) }}">
                    @error('office_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">نبذة تعريفية</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $instructor->bio) }}</textarea>
                    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">الصورة الشخصية (اختياري)</label>
                    <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture">
                    @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($instructor->profile_picture_url)
                        <div class="mt-2">
                            <img src="{{ Storage::url($instructor->profile_picture_url) }}" alt="الصورة الحالية" style="width: 100px; height: 100px; object-fit: cover;">
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
                        <option value="1" {{ old('is_active', $instructor->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ old('is_active', $instructor->is_active) == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث البيانات</button>
                    <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection