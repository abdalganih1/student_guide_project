@extends('admin.layouts.app')

@section('title', 'إدارة المشاريع')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-project-diagram me-2"></i>إدارة المشاريع</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة مشروع جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.projects.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search_filter" class="form-label">بحث (عنوان، طلاب، كلمات مفتاحية)</label>
                        <input type="text" class="form-control form-control-sm" id="search_filter" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="specialization_id_filter" class="form-label">الاختصاص</label>
                        <select class="form-select form-select-sm" id="specialization_id_filter" name="specialization_id">
                            <option value="">-- كل الاختصاصات --</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year_filter" class="form-label">السنة</label>
                        <select class="form-select form-select-sm" id="year_filter" name="year">
                            <option value="">-- كل السنوات --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="semester_filter" class="form-label">الفصل</label>
                        <select class="form-select form-select-sm" id="semester_filter" name="semester">
                            <option value="">-- كل الفصول --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">فلترة</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-sm w-100">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($projects->isEmpty())
                <div class="alert alert-info text-center">لا توجد مشاريع تخرج لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>العنوان (عربي)</th>
                                <th>الاختصاص</th>
                                <th>المشرف</th>
                                <th>السنة</th>
                                <th>الفصل</th>
                                <th>الطلاب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>
                                    <a href="{{ route('admin.projects.show', $project) }}">{{ Str::limit($project->title_ar, 50) }}</a>
                                    @if($project->title_en) <small class="d-block text-muted">{{ Str::limit($project->title_en, 50) }}</small> @endif
                                </td>
                                <td>
                                    @forelse($project->specializations as $specialization)
                                        <span class="badge bg-info">{{ $specialization->name_ar }}</span>{{ !$loop->last ? ',' : '' }}
                                    @empty
                                        -
                                    @endforelse
                                </td>                                <td>{{ $project->supervisor->name_ar ?? '-' }}</td>
                                <td>{{ $project->year }}</td>
                                <td>{{ $project->semester }}</td>
                                <td>{{ Str::limit($project->student_names, 30) ?: '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المشروع؟');">
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
                    {{ $projects->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection