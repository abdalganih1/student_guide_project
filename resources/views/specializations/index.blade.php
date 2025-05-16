@extends('admin.layouts.app')

@section('title', 'إدارة الاختصاصات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-sitemap me-2"></i>إدارة الاختصاصات</h1>
        <a href="{{ route('admin.specializations.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة اختصاص جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.specializations.index') }}">
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            @if($specializations->isEmpty())
                <div class="alert alert-info text-center">لا توجد اختصاصات لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم (عربي)</th>
                                <th>الاسم (إنجليزي)</th>
                                <th>الكلية</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specializations as $specialization)
                            <tr>
                                <td>{{ $specialization->id }}</td>
                                <td>{{ $specialization->name_ar }}</td>
                                <td>{{ $specialization->name_en ?: '-' }}</td>
                                <td>{{ $specialization->faculty->name_ar ?? 'غير محدد' }}</td>
                                <td>
                                    @if($specialization->status == 'published')
                                        <span class="badge bg-success">منشور</span>
                                    @elseif($specialization->status == 'draft')
                                        <span class="badge bg-warning text-dark">مسودة</span>
                                    @elseif($specialization->status == 'archived')
                                        <span class="badge bg-secondary">مؤرشف</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $specialization->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $specialization->created_at->translatedFormat('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.specializations.show', $specialization) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.specializations.edit', $specialization) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.specializations.destroy', $specialization) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الاختصاص؟ سيؤدي هذا إلى حذف جميع المقررات والمشاريع المرتبطة به.');">
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
                    {{ $specializations->appends(request()->query())->links() }} {{-- للحفاظ على الفلاتر عند التصفح --}}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection