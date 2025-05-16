@extends('admin.layouts.app')

@section('title', 'إدارة الطلاب')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-graduate me-2"></i>إدارة الطلاب</h1>
        <a href="{{ route('admin.students.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus me-1"></i> إضافة طالب جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.students.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search_filter" class="form-label">بحث (اسم، رقم جامعي، بريد)</label>
                        <input type="text" class="form-control form-control-sm" id="search_filter" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="specialization_id_filter" class="form-label">الاختصاص</label>
                        <select class="form-select form-select-sm" id="specialization_id_filter" name="specialization_id">
                            <option value="">-- الكل --</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="enrollment_year_filter" class="form-label">سنة الالتحاق</label>
                        <select class="form-select form-select-sm" id="enrollment_year_filter" name="enrollment_year">
                            <option value="">-- الكل --</option>
                            @foreach($enrollmentYears as $year)
                                <option value="{{ $year }}" {{ request('enrollment_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_active_filter" class="form-label">الحالة</label>
                        <select class="form-select form-select-sm" id="is_active_filter" name="is_active">
                            <option value="">-- الكل --</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') == '0' && request()->filled('is_active') ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">فلترة</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm w-100">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($students->isEmpty())
                <div class="alert alert-info text-center">لا يوجد طلاب لعرضهم حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الرقم الجامعي</th>
                                <th>الاسم (عربي)</th>
                                <th>البريد الإلكتروني</th>
                                <th>الاختصاص</th>
                                <th>سنة الالتحاق</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>
                                    @if($student->profile_picture_url)
                                        <img src="{{ Storage::url($student->profile_picture_url) }}" alt="{{ $student->full_name_ar }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user fa-2x text-secondary"></i>
                                    @endif
                                </td>
                                <td>{{ $student->student_university_id }}</td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student) }}">{{ $student->full_name_ar }}</a>
                                    @if($student->full_name_en) <small class="d-block text-muted">{{ $student->full_name_en }}</small>@endif
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->specialization->name_ar ?? '-' }}</td>
                                <td>{{ $student->enrollment_year ?: '-' }}</td>
                                <td>
                                    @if($student->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الطالب؟');">
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
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection