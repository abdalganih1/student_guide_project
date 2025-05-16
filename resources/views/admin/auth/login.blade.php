@extends('admin.layouts.app')

@section('title', 'تسجيل دخول المدير')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">تسجيل دخول لوحة التحكم</div>
                <div class="card-body">
                    {{-- معلومات النسخ واللصق --}}
                    <div class="alert alert-info small">
                        <p class="mb-1"><strong>بيانات تسجيل الدخول التجريبية (Super Admin):</strong></p>
                        <p class="mb-1">اسم المستخدم: <code>superadmin</code></p>
                        <p class="mb-0">كلمة المرور: <code>password</code></p>
                        <hr>
                        <p class="mb-1"><strong>بيانات تسجيل الدخول التجريبية (Content Manager):</strong></p>
                        <p class="mb-1">اسم المستخدم: <code>contentmanager</code></p>
                        <p class="mb-0">كلمة المرور: <code>password</code></p>
                    </div>

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login_field" class="form-label">اسم المستخدم أو البريد الإلكتروني</label>
                            <input type="text" class="form-control @error('login_field') is-invalid @enderror" id="login_field" name="login_field" value="{{ old('login_field') }}" required autofocus>
                            @error('login_field')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">تذكرني</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillSuperAdminCredentials()">ملء بيانات Super Admin</button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="fillContentManagerCredentials()">ملء بيانات Content Manager</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillSuperAdminCredentials() {
        document.getElementById('login_field').value = 'superadmin'; // أو 'superadmin@example.com' حسب إعداداتك
        document.getElementById('password').value = 'password';
    }
    function fillContentManagerCredentials() {
        document.getElementById('login_field').value = 'contentmanager'; // أو 'contentmanager@example.com'
        document.getElementById('password').value = 'password';
    }
</script>
@endpush