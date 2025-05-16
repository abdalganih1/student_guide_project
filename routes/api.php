<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers for API
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SpecializationController as ApiSpecializationController;
use App\Http\Controllers\Api\CourseController as ApiCourseController; // تم استيراده بالفعل
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
*/

// Public API Routes (No Authentication Needed)
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Specializations
Route::get('/specializations', [ApiSpecializationController::class, 'index'])->name('api.specializations.index');
Route::get('/specializations/{specialization}', [ApiSpecializationController::class, 'show'])->name('api.specializations.show');
Route::get('/specializations/{specialization}/courses', [ApiCourseController::class, 'getCoursesBySpecialization'])->name('api.specializations.courses');

// Courses
Route::get('/courses', [ApiCourseController::class, 'index'])->name('api.courses.index');
Route::get('/courses/year-level/{yearLevel}', [ApiCourseController::class, 'getCoursesByYearLevel'])->name('api.courses.byYearLevel');
Route::get('/courses/{course}', [ApiCourseController::class, 'show'])->name('api.courses.show');

// Instructors
Route::get('/instructors', [ApiInstructorController::class, 'index'])->name('api.instructors.index');
Route::get('/instructors/{instructor}', [ApiInstructorController::class, 'show'])->name('api.instructors.show');

// Projects
Route::get('/projects', [ApiProjectController::class, 'index'])->name('api.projects.index');
Route::get('/projects/filter', [ApiProjectController::class, 'filterProjects'])->name('api.projects.filter');
Route::get('/projects/{project}', [ApiProjectController::class, 'show'])->name('api.projects.show');

// University Facilities
Route::get('/university-facilities', [ApiUniversityFacilityController::class, 'index'])->name('api.university-facilities.index');
Route::get('/university-facilities/category/{categorySlug}', [ApiUniversityFacilityController::class, 'getFacilitiesByCategory'])->name('api.university-facilities.byCategory');
Route::get('/university-facilities/{universityMedia}', [ApiUniversityFacilityController::class, 'show'])->name('api.university-facilities.show');

// Events
Route::get('/events', [ApiEventController::class, 'index'])->name('api.events.index');
Route::get('/events/{event}', [ApiEventController::class, 'show'])->name('api.events.show');

// Search
Route::get('/search', [ApiSearchController::class, 'search'])->name('api.search');


// Authenticated API Routes (Require Student Authentication - Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.user');

    // Event Registration
    Route::post('/events/{event}/register', [ApiEventController::class, 'register'])->name('api.events.register');

    // Course Registration/Enrollment <<-- جديد
    Route::post('/courses/{course}/enroll', [ApiCourseController::class, 'enroll'])->name('api.courses.enroll');

    // Notifications
    Route::get('/notifications', [ApiNotificationController::class, 'index'])->name('api.notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [ApiNotificationController::class, 'markAsRead'])->name('api.notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [ApiNotificationController::class, 'markAllAsRead'])->name('api.notifications.markAllAsRead');

    // Student Profile Management
    Route::get('/profile', [ApiStudentProfileController::class, 'show'])->name('api.profile.show');
    Route::put('/profile', [ApiStudentProfileController::class, 'update'])->name('api.profile.update');
    Route::post('/profile/change-password', [ApiStudentProfileController::class, 'changePassword'])->name('api.profile.changePassword');

    // يمكنك إضافة مسارات أخرى تتطلب مصادقة الطالب هنا
    // مثال: عرض المقررات المسجل بها الطالب حاليًا
    // Route::get('/my-courses', [ApiStudentProfileController::class, 'myCourses'])->name('api.myCourses');
});