@extends('admin.layouts.app')

@section('title', 'إدارة التنبيهات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-bell me-2"></i>إدارة التنبيهات</h1>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إرسال تنبيه جديد
        </a>
    </div>

    {{-- يمكنك إضافة قسم للفلترة هنا إذا أردت (مثلاً حسب النوع، المرسل، إلخ) --}}

    <div class="card">
        <div class="card-body">
            @if($notifications->isEmpty())
                <div class="alert alert-info text-center">لا توجد تنبيهات لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>العنوان (عربي)</th>
                                <th>النوع</th>
                                <th>الجمهور المستهدف</th>
                                <th>تاريخ النشر</th>
                                <th>المرسل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                            <tr>
                                <td>{{ $notification->id }}</td>
                                <td>
                                    <a href="{{ route('admin.notifications.show', $notification) }}">{{ Str::limit($notification->title_ar, 50) }}</a>
                                    @if($notification->title_en)<small class="d-block text-muted">{{ Str::limit($notification->title_en, 50) }}</small>@endif
                                </td>
                                <td>{{ $notification->type }}</td>
                                <td>
                                    @if($notification->target_audience_type == 'all')
                                        الكل
                                    @elseif($notification->target_audience_type == 'course_specific' && $notification->relatedCourse)
                                        طلاب مقرر: <a href="{{ route('admin.courses.show', $notification->relatedCourse) }}">{{ $notification->relatedCourse->name_ar }}</a>
                                    @elseif($notification->target_audience_type == 'custom_group' || $notification->target_audience_type == 'individual')
                                        مجموعة مخصصة/أفراد ({{ $notification->recipients->count() }})
                                    @else
                                        {{ $notification->target_audience_type }}
                                    @endif
                                </td>
                                <td>{{ $notification->publish_datetime->translatedFormat('Y-m-d H:i') }}</td>
                                <td>{{ $notification->sentByAdmin->username ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-sm btn-info" title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    {{-- عادة لا يتم تعديل أو حذف التنبيهات المرسلة --}}
                                    
                                    <form action="{{-- route('admin.notifications.destroy', $notification) --}}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا التنبيه؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger disabled" title="حذف"><i class="fas fa-trash"></i></button>
                                    </form>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection