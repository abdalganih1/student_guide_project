@extends('admin.layouts.app')

@section('title', 'إدارة وسائط الجامعة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-photo-video me-2"></i>إدارة وسائط الجامعة</h1>
        <a href="{{ route('admin.university-facilities.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة وسيط جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.university-facilities.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="category_filter" class="form-label">التصنيف</label>
                        <select class="form-select form-select-sm" id="category_filter" name="category">
                            <option value="">-- كل التصنيفات --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="media_type_filter" class="form-label">نوع الوسيط</label>
                        <select class="form-select form-select-sm" id="media_type_filter" name="media_type">
                            <option value="">-- كل الأنواع --</option>
                            @foreach($mediaTypes as $typeKey => $typeName)
                                <option value="{{ $typeKey }}" {{ request('media_type') == $typeKey ? 'selected' : '' }}>
                                    {{ $typeName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="faculty_id_filter" class="form-label">الكلية</label>
                        <select class="form-select form-select-sm" id="faculty_id_filter" name="faculty_id">
                            <option value="">-- كل الكليات --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">فلترة</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.university-facilities.index') }}" class="btn btn-secondary btn-sm w-100">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($mediaItems->isEmpty())
                <div class="alert alert-info text-center">لا توجد وسائط جامعية لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>معاينة</th>
                                <th>العنوان (عربي)</th>
                                <th>النوع</th>
                                <th>التصنيف</th>
                                <th>الكلية</th>
                                <th>رفع بواسطة</th>
                                <th>تاريخ الرفع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mediaItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->media_type == 'image' && $item->file_url)
                                        <a href="{{ Storage::url($item->file_url) }}" target="_blank">
                                            <img src="{{ Storage::url($item->file_url) }}" alt="{{ $item->title_ar ?: 'صورة' }}" class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                        </a>
                                    @elseif($item->media_type == 'video' && $item->file_url)
                                        <a href="{{ Storage::url($item->file_url) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-video"></i> فيديو</a>
                                    @elseif($item->media_type == 'document' && $item->file_url)
                                        <a href="{{ Storage::url($item->file_url) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-alt"></i> مستند</a>
                                    @else
                                        <i class="fas fa-file fa-2x text-secondary"></i>
                                    @endif
                                </td>
                                <td>{{ $item->title_ar ?: (Str::limit(basename($item->file_url), 30) ?: '-') }}</td>
                                <td>{{ $mediaTypes[$item->media_type] ?? $item->media_type }}</td>
                                <td>{{ $item->category ?: '-' }}</td>
                                <td>{{ $item->faculty->name_ar ?? '-' }}</td>
                                <td>{{ $item->uploadedByAdmin->username ?? '-' }}</td>
                                <td>{{ $item->created_at->translatedFormat('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.university-facilities.show', $item) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.university-facilities.edit', $item) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.university-facilities.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الوسيط؟');">
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
                    {{ $mediaItems->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection