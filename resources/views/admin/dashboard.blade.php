@extends('admin.layouts.app')

@section('title', 'لوحة القيادة')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">لوحة القيادة</h1>
    <div class="row">
        {{-- الطلاب --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-graduate me-2"></i>الطلاب</span>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
                <div class="card-body">
                    <h3 class="card-title display-4">{{ $stats['totalStudents'] ?? '0' }}</h3>
                    <p class="card-text">إجمالي عدد الطلاب المسجلين.</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="card-footer text-white clearfix small z-1">
                    <span class="float-start">عرض التفاصيل</span>
                    <span class="float-end"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>

        {{-- المقررات --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-book-open me-2"></i>المقررات</span>
                    <i class="fas fa-book fa-2x opacity-50"></i>
                </div>
                <div class="card-body">
                    <h3 class="card-title display-4">{{ $stats['totalCourses'] ?? '0' }}</h3>
                    <p class="card-text">إجمالي عدد المقررات المتاحة.</p>
                </div>
                 <a href="{{ route('admin.courses.index') }}" class="card-footer text-white clearfix small z-1">
                    <span class="float-start">عرض التفاصيل</span>
                    <span class="float-end"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>

        {{-- المدرسون --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-info h-100">
                 <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chalkboard-teacher me-2"></i>المدرسون</span>
                    <i class="fas fa-user-tie fa-2x opacity-50"></i>
                </div>
                <div class="card-body">
                    <h3 class="card-title display-4">{{ $stats['totalInstructors'] ?? '0' }}</h3>
                    <p class="card-text">إجمالي عدد المدرسين.</p>
                </div>
                <a href="{{ route('admin.instructors.index') }}" class="card-footer text-white clearfix small z-1">
                    <span class="float-start">عرض التفاصيل</span>
                    <span class="float-end"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>

        {{-- الفعاليات النشطة --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-alt me-2"></i>الفعاليات النشطة</span>
                     <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
                <div class="card-body">
                    <h3 class="card-title display-4">{{ $stats['totalEvents'] ?? '0' }}</h3>
                    <p class="card-text">الفعاليات المجدولة أو الجارية حالياً.</p>
                </div>
                <a href="{{ route('admin.events.index') }}" class="card-footer text-white clearfix small z-1">
                    <span class="float-start">عرض التفاصيل</span>
                    <span class="float-end"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>

         {{-- مشاريع التخرج --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-white bg-danger h-100">
                 <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-project-diagram me-2"></i>مشاريع التخرج</span>
                    <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                </div>
                <div class="card-body">
                    <h3 class="card-title display-4">{{ $stats['totalProjects'] ?? '0' }}</h3>
                    <p class="card-text">إجمالي عدد مشاريع التخرج.</p>
                </div>
                 <a href="{{ route('admin.projects.index') }}" class="card-footer text-white clearfix small z-1">
                    <span class="float-start">عرض التفاصيل</span>
                    <span class="float-end"><i class="fas fa-angle-right"></i></span>
                </a>
            </div>
        </div>

    </div>

    {{-- يمكنك إضافة المزيد من الإحصائيات والمخططات هنا --}}
    {{-- مثال: آخر الطلاب المسجلين، آخر المقررات المضافة، إلخ. --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i> آخر الطلاب المسجلين
                </div>
                <div class="card-body">
                    @php
                        $recentStudents = \App\Models\Student::latest()->take(5)->get();
                    @endphp
                    @if($recentStudents->isEmpty())
                        <p class="text-muted">لا يوجد طلاب جدد.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentStudents as $student)
                                <li class="list-group-item">
                                    <a href="{{ route('admin.students.show', $student) }}">{{ $student->full_name_ar }}</a>
                                    <small class="text-muted float-end">{{ $student->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bell me-1"></i> آخر التنبيهات المرسلة
                </div>
                <div class="card-body">
                     @php
                        $recentNotifications = \App\Models\Notification::latest()->take(5)->get();
                    @endphp
                    @if($recentNotifications->isEmpty())
                        <p class="text-muted">لا توجد تنبيهات حديثة.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentNotifications as $notification)
                                <li class="list-group-item">
                                    <a href="{{ route('admin.notifications.show', $notification) }}">{{ Str::limit($notification->title_ar, 40) }}</a>
                                    <small class="text-muted float-end">{{ $notification->publish_datetime->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection