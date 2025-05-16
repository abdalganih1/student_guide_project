@extends('admin.layouts.app')

@section('title', 'إدارة المدرسين')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-chalkboard-teacher me-2"></i>إدارة المدرسين</h1>
        <a href="{{ route('admin.instructors.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة مدرس جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.instructors.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="faculty_id_filter" class="form-label">فلترة حسب الكلية</label>
                        <select class="form-select" id="faculty_id_filter" name="faculty_id">
                            <option value="">-- كل الكليات --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- يمكنك إضافة فلاتر أخرى هنا (مثل الحالة: نشط/غير نشط) --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($instructors->isEmpty())
                <div class="alert alert-info text-center">لا يوجد مدرسون لعرضهم حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم (عربي)</th>
                                <th>الاسم (إنجليزي)</th>
                                <th>اللقب</th>
                                <th>البريد الإلكتروني</th>
                                <th>الكلية</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instructors as $instructor)
                            <tr>
                                <td>{{ $instructor->id }}</td>
                                <td>
                                    @if($instructor->profile_picture_url)
                                        <img src="{{ Storage::url($instructor->profile_picture_url) }}" alt="{{ $instructor->name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
                                    @endif
                                </td>
                                <td>{{ $instructor->name_ar }}</td>
                                <td>{{ $instructor->name_en ?: '-' }}</td>
                                <td>{{ $instructor->title ?: '-' }}</td>
                                <td>{{ $instructor->email ?: '-' }}</td>
                                <td>{{ $instructor->faculty->name_ar ?? 'غير محدد' }}</td>
                                <td>
                                    @if($instructor->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.instructors.show', $instructor) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.instructors.edit', $instructor) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.instructors.destroy', $instructor) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المدرس؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $instructors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection