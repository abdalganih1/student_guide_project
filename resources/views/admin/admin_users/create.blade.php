@extends('admin.layouts.app')

@section('title', 'إضافة مدير نظام جديد')

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-user-shield me-2"></i>إضافة مدير نظام جديد</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.admin-users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                        @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}">
                        @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            @foreach($roles as $roleValue)
                                <option value="{{ $roleValue }}" {{ old('role') == $roleValue ? 'selected' : '' }}>
                                    @if($roleValue == 'superadmin') مدير عام
                                    @elseif($roleValue == 'content_manager') مدير محتوى
                                    @else {{ ucfirst(str_replace('_', ' ', $roleValue)) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> حفظ المدير</button>
                    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection