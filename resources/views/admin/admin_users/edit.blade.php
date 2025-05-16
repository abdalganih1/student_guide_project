@extends('admin.layouts.app')

@section('title', 'تعديل مدير النظام: ' . $adminUser->username)

@section('content')
<div class="container-fluid">
    <h1><i class="fas fa-user-edit me-2"></i>تعديل مدير النظام: {{ $adminUser->username }}</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.admin-users.update', $adminUser) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">اسم المستخدم <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $adminUser->username) }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $adminUser->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $adminUser->name_ar) }}" required>
                        @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $adminUser->name_en) }}">
                        @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور الجديدة (اتركه فارغًا لعدم التغيير)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">الدور <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required {{ ($adminUser->role === 'superadmin' && App\Models\AdminUser::where('role', 'superadmin')->count() <= 1 && Auth::guard('admin_web')->id() === $adminUser->id) ? 'disabled' : '' }}>
                            @foreach($roles as $roleValue)
                                <option value="{{ $roleValue }}" {{ old('role', $adminUser->role) == $roleValue ? 'selected' : '' }}>
                                    @if($roleValue == 'superadmin') مدير عام
                                    @elseif($roleValue == 'content_manager') مدير محتوى
                                    @else {{ ucfirst(str_replace('_', ' ', $roleValue)) }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if(($adminUser->role === 'superadmin' && App\Models\AdminUser::where('role', 'superadmin')->count() <= 1 && Auth::guard('admin_web')->id() === $adminUser->id))
                            <small class="form-text text-warning">لا يمكن تغيير دور آخر مدير عام.</small>
                            <input type="hidden" name="role" value="{{ $adminUser->role }}"> {{-- لإرسال القيمة القديمة إذا كان الحقل معطلاً --}}
                        @endif
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required {{ (Auth::guard('admin_web')->id() === $adminUser->id && !$adminUser->is_active) ? 'disabled' : '' }}>
                            <option value="1" {{ old('is_active', $adminUser->is_active) == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ old('is_active', $adminUser->is_active) == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                         @if(Auth::guard('admin_web')->id() === $adminUser->id && !$adminUser->is_active)
                            <small class="form-text text-warning">لا يمكنك تعطيل حسابك الخاص.</small>
                             <input type="hidden" name="is_active" value="0">
                        @endif
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> تحديث البيانات</button>
                    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection