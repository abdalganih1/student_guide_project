@extends('admin.layouts.app')

@section('title', 'لوحة القيادة')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">لوحة القيادة</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">الطلاب</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalStudents ?? 'N/A' }}</h5>
                    <p class="card-text">إجمالي عدد الطلاب المسجلين.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">المقررات</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalCourses ?? 'N/A' }}</h5>
                    <p class="card-text">إجمالي عدد المقررات المتاحة.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">المدرسون</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalInstructors ?? 'N/A' }}</h5>
                    <p class="card-text">إجمالي عدد المدرسين.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- يمكنك إضافة المزيد من الإحصائيات والمخططات هنا -->
</div>
@endsection