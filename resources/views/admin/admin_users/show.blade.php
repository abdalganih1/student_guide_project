@extends('admin.layouts.app')

@section('title', 'تفاصيل مدير النظام: ' . $adminUser->username)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-user-shield me-2"></i>تفاصيل مدير النظام: {{ $adminUser->username }}</h1>
        <div>
            @if(Auth::guard('admin_web')->user()->role === 'superadmin')
                <a href="{{ route('admin.admin-users.edit', $adminUser) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            @endif
            <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">اسم المستخدم:</dt>
                <dd class="col-sm-9">{{ $adminUser->username }}</dd>

                <dt class="col-sm-3">الاسم (عربي):</dt>
                <dd class="col-sm-9">{{ $adminUser->name_ar }}</dd>

                <dt class="col-sm-3">الاسم (إنجليزي):</dt>
                <dd class="col-sm-9">{{ $adminUser->name_en ?: '-' }}</dd>

                <dt class="col-sm-3">البريد الإلكتروني:</dt>
                <dd class="col-sm-9">{{ $adminUser->email }}</dd>

                <dt class="col-sm-3">الدور:</dt>
                <dd class="col-sm-9">
                    @if($adminUser->role == 'superadmin') مدير عام
                    @elseif($adminUser->role == 'content_manager') مدير محتوى
                    @else {{ $adminUser->role }}
                    @endif
                </dd>

                <dt class="col-sm-3">الحالة:</dt>
                <dd class="col-sm-9">{{ $adminUser->is_active ? 'نشط' : 'غير نشط' }}</dd>

                <dt class="col-sm-3">تاريخ الإضافة:</dt>
                <dd class="col-sm-9">{{ $adminUser->created_at->translatedFormat('l, d F Y H:i') }}</dd>

                <dt class="col-sm-3">آخر تحديث:</dt>
                <dd class="col-sm-9">{{ $adminUser->updated_at->translatedFormat('l, d F Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection