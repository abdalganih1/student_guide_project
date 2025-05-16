@extends('admin.layouts.app')

@section('title', 'إدارة مديري النظام')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users-cog me-2"></i>إدارة مديري النظام</h1>
        @if(Auth::guard('admin_web')->user()->role === 'superadmin')
            <a href="{{ route('admin.admin-users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> إضافة مدير جديد
            </a>
        @endif
    </div>

    {{-- يمكنك إضافة قسم للفلترة هنا إذا أردت (مثلاً حسب الدور، الحالة) --}}

    <div class="card">
        <div class="card-body">
            @if($adminUsers->isEmpty())
                <div class="alert alert-info text-center">لا يوجد مديرو نظام لعرضهم حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم المستخدم</th>
                                <th>الاسم (عربي)</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور</th>
                                <th>الحالة</th>
                                <th>تاريخ الإضافة</th>
                                @if(Auth::guard('admin_web')->user()->role === 'superadmin')
                                    <th>الإجراءات</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adminUsers as $adminUser)
                            <tr>
                                <td>{{ $adminUser->id }}</td>
                                <td>{{ $adminUser->username }}</td>
                                <td>
                                    <a href="{{ route('admin.admin-users.show', $adminUser) }}">{{ $adminUser->name_ar }}</a>
                                    @if($adminUser->name_en) <small class="d-block text-muted">{{ $adminUser->name_en }}</small>@endif
                                </td>
                                <td>{{ $adminUser->email }}</td>
                                <td>
                                    @if($adminUser->role == 'superadmin')
                                        <span class="badge bg-danger">مدير عام</span>
                                    @elseif($adminUser->role == 'content_manager')
                                        <span class="badge bg-info">مدير محتوى</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $adminUser->role }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($adminUser->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>{{ $adminUser->created_at->translatedFormat('Y-m-d') }}</td>
                                @if(Auth::guard('admin_web')->user()->role === 'superadmin')
                                <td>
                                    <a href="{{ route('admin.admin-users.show', $adminUser) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.admin-users.edit', $adminUser) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    @if(Auth::guard('admin_web')->id() !== $adminUser->id && !($adminUser->role === 'superadmin' && App\Models\AdminUser::where('role', 'superadmin')->count() <= 1))
                                        <form action="{{ route('admin.admin-users.destroy', $adminUser) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المدير؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger disabled" title="لا يمكن حذف هذا المستخدم"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $adminUsers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection