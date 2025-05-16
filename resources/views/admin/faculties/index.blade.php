@extends('admin.layouts.app')

@section('title', 'إدارة الكليات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>إدارة الكليات</h1>
        <a href="{{ route('admin.faculties.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة كلية جديدة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($faculties->isEmpty())
                <div class="alert alert-info">لا توجد كليات حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم (عربي)</th>
                                <th>الاسم (إنجليزي)</th>
                                <th>العميد</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faculties as $faculty)
                            <tr>
                                <td>{{ $faculty->id }}</td>
                                <td>{{ $faculty->name_ar }}</td>
                                <td>{{ $faculty->name_en ?: '-' }}</td>
                                <td>{{ $faculty->dean ? $faculty->dean->name_ar : '-' }}</td>
                                <td>{{ $faculty->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.faculties.show', $faculty) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الكلية؟');">
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
                    {{ $faculties->links() }} {{-- لعرض روابط التصفح --}}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection