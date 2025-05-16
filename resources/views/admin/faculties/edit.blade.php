@extends('admin.layouts.app')

@section('title', 'تعديل بيانات الكلية')

@section('content')
<div class="container-fluid">
    <h1>تعديل بيانات الكلية: {{ $faculty->name_ar }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.faculties.update', $faculty->id) }}" method="POST">
                @csrf
                @method('PUT') {{--  أو @method('PATCH') حسب تفضيلك في التوجيه  --}}

                <div class="mb-3">
                    <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $faculty->name_ar) }}" required>
                    @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $faculty->name_en) }}">
                    @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $faculty->description_ar) }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $faculty->description_en) }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="dean_id" class="form-label">عميد الكلية (اختياري)</label>
                    <select class="form-select @error('dean_id') is-invalid @enderror" id="dean_id" name="dean_id">
                        <option value="">-- اختر العميد --</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('dean_id', $faculty->dean_id) == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name_ar }} ({{ $instructor->name_en }})
                            </option>
                        @endforeach
                    </select>
                    @error('dean_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">تحديث الكلية</button>
                <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection