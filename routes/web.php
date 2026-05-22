<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherWorkspaceController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SubjectController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/signup', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('signed-in')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/scores', [AdminController::class, 'dashboard'])->name('scores');
        Route::get('/upload', [AdminController::class, 'showUpload'])->name('upload');
        Route::post('/upload', [AdminController::class, 'upload'])->name('upload.store');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::resource('classes', SchoolClassController::class)->except(['show'])->parameters(['classes' => 'class']);
        Route::resource('subjects', SubjectController::class)->except(['show']);
        Route::post('/teacher-assignments', [TeacherController::class, 'storeAssignment'])->name('assignments.store');
        Route::delete('/teacher-assignments/{teacherAssignment}', [TeacherController::class, 'destroyAssignment'])->name('assignments.destroy');
    });

    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherWorkspaceController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes/{teacherAssignment}/attendance', [TeacherWorkspaceController::class, 'attendance'])->name('attendance');
        Route::post('/classes/{teacherAssignment}/attendance', [TeacherWorkspaceController::class, 'storeAttendance'])->name('attendance.store');
        Route::put('/attendance/{attendanceSession}', [TeacherWorkspaceController::class, 'updateAttendance'])->name('attendance.update');
        Route::get('/classes/{teacherAssignment}/scores', [TeacherWorkspaceController::class, 'scores'])->name('scores');
        Route::post('/classes/{teacherAssignment}/quizzes', [TeacherWorkspaceController::class, 'storeQuiz'])->name('quizzes.store');
        Route::put('/classes/{teacherAssignment}/quizzes/{quiz}', [TeacherWorkspaceController::class, 'updateQuizScores'])->name('quizzes.update');
        Route::put('/classes/{teacherAssignment}/midterms', [TeacherWorkspaceController::class, 'updateMidterms'])->name('midterms.update');
        Route::get('/classes/{teacherAssignment}/students/{student}', [TeacherWorkspaceController::class, 'studentPerformance'])->name('students.performance');
    });

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    });
});
