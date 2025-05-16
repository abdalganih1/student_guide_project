<?php

use Illuminate\Support\Facades\Route;

// Admin Auth Controllers
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

// Admin Panel Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacultyController as AdminFacultyController;
use App\Http\Controllers\Admin\SpecializationController as AdminSpecializationController;
use App\Http\Controllers\Admin\InstructorController as AdminInstructorController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController; // لمشاريع التخرج
use App\Http\Controllers\Admin\UniversityFacilityController as AdminUniversityFacilityController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\EventRegistrationController as AdminEventRegistrationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\AdminUserController as AdminAdminUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // يمكنك توجيه المستخدم إلى صفحة تسجيل دخول الطلاب أو لوحة تحكم الإدارة
    // أو عرض صفحة رئيسية عامة إذا كان لديك.
    // return view('welcome');
    return redirect()->route('admin.login.form'); // مثال: توجيه إلى صفحة تسجيل دخول المدير
});


// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout')->middleware('auth:admin_web'); // يتطلب تسجيل الدخول للخروج
});


// Admin Panel Routes (Protected by admin_web guard)
Route::prefix('admin')->name('admin.')->middleware('auth:admin_web')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Resourceful routes for CRUD operations
    Route::resource('faculties', AdminFacultyController::class); // لإدارة الكليات
    Route::resource('specializations', AdminSpecializationController::class);
    Route::resource('instructors', AdminInstructorController::class);
    Route::resource('courses', AdminCourseController::class);
    Route::resource('projects', AdminProjectController::class); // لمشاريع التخرج
    Route::resource('university-facilities', AdminUniversityFacilityController::class); // لإدارة الوسائط (الصور والفيديو)
    Route::resource('events', AdminEventController::class);
    Route::resource('students', AdminStudentController::class); // لإدارة بيانات الطلاب
    Route::resource('admin-users', AdminAdminUserController::class)->except(['show']); // لإدارة مستخدمي لوحة التحكم (إذا لزم الأمر)

    // Event Registrations Management
    Route::prefix('event-registrations')->name('event-registrations.')->group(function () {
        Route::get('/', [AdminEventRegistrationController::class, 'index'])->name('index');
        Route::post('{registration}/approve', [AdminEventRegistrationController::class, 'approve'])->name('approve');
        Route::post('{registration}/reject', [AdminEventRegistrationController::class, 'reject'])->name('reject');
        Route::get('{registration}', [AdminEventRegistrationController::class, 'show'])->name('show'); // اختياري
    });

    // Notifications Management
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/create', [AdminNotificationController::class, 'create'])->name('create');
        Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
        Route::get('/{notification}', [AdminNotificationController::class, 'show'])->name('show');
        // يمكنك إضافة مسارات لتعديل أو حذف الإشعارات المجدولة إذا أردت
        // Route::get('/{notification}/edit', [AdminNotificationController::class, 'edit'])->name('edit');
        // Route::put('/{notification}', [AdminNotificationController::class, 'update'])->name('update');
        // Route::delete('/{notification}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });

    // يمكنك إضافة مسارات أخرى خاصة بلوحة التحكم هنا
    // على سبيل المثال، إدارة موارد المقررات داخل صفحة تعديل المقرر، أو تعيين المدرسين للمقررات.
    // مثال:
    // Route::get('courses/{course}/resources', [AdminCourseResourceController::class, 'index'])->name('courses.resources.index');
    // Route::post('courses/{course}/assign-instructor', [AdminCourseController::class, 'assignInstructor'])->name('courses.assignInstructor');
});

// إذا كنت ستستخدم نظام مصادقة Laravel الافتراضي للطلاب (الذي عدلناه) عبر الويب أيضًا،
// يمكنك تفعيل المسارات التي يوفرها Laravel UI أو Breeze أو Jetstream هنا.
// مثال إذا كنت تستخدم Laravel UI:
// Auth::routes(); // هذا سيفعل مسارات تسجيل الدخول، التسجيل، إعادة تعيين كلمة المرور للطلاب عبر الويب
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); // صفحة رئيسية بعد تسجيل دخول الطالب
