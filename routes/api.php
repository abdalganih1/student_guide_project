<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers for API
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpecializationController as ApiSpecializationController;
use App\Http\Controllers\Api\CourseController as ApiCourseController;
use App\Http\Controllers\Api\InstructorController as ApiInstructorController;
use App\Http\Controllers\Api\ProjectController as ApiProjectController;
use App\Http\Controllers\Api\UniversityFacilityController as ApiUniversityFacilityController;
use App\Http\Controllers\Api\EventController as ApiEventController;
use App\Http\Controllers\Api\SearchController as ApiSearchController;
use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Controllers\Api\StudentProfileController as ApiStudentProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (No Authentication Needed)
Route::post('/login', [AuthController::class, 'login']); // تسجيل دخول الطالب

Route::get('/specializations', [ApiSpecializationController::class, 'index']);
Route::get('/specializations/{specialization}', [ApiSpecializationController::class, 'show']); // {specialization} هو ID
Route::get('/specializations/{specialization}/courses', [ApiCourseController::class, 'getCoursesBySpecialization']); // مسار بديل

Route::get('/courses', [ApiCourseController::class, 'index']);
Route::get('/courses/{course}', [ApiCourseController::class, 'show']);

Route::get('/instructors', [ApiInstructorController::class, 'index']);
Route::get('/instructors/{instructor}', [ApiInstructorController::class, 'show']); // اختياري إذا كان هناك صفحة لملفه الشخصي

Route::get('/projects', [ApiProjectController::class, 'index']); // أرشيف مشاريع التخرج
Route::get('/projects/{project}', [ApiProjectController::class, 'show']);

Route::get('/university-facilities', [ApiUniversityFacilityController::class, 'index']);
Route::get('/university-facilities/{universityMedia}', [ApiUniversityFacilityController::class, 'show']);

Route::get('/events', [ApiEventController::class, 'index']);
Route::get('/events/{event}', [ApiEventController::class, 'show']);

Route::get('/search', [ApiSearchController::class, 'search']);


// Authenticated API Routes (Require Student Authentication - Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // جلب بيانات الطالب المسجل

    // Event Registration
    Route::post('/events/{event}/register', [ApiEventController::class, 'register']);

    // Notifications
    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::post('/notifications/{notification}/mark-as-read', [ApiNotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [ApiNotificationController::class, 'markAllAsRead']);

    // Student Profile Management (Optional)
    Route::get('/profile', [ApiStudentProfileController::class, 'show']);
    Route::put('/profile', [ApiStudentProfileController::class, 'update']); // أو post إذا كنت تفضل
    Route::post('/profile/change-password', [ApiStudentProfileController::class, 'changePassword']);

    // يمكنك إضافة مسارات أخرى تتطلب مصادقة الطالب هنا
    // مثل عرض المقررات المسجل بها، إلخ.
});