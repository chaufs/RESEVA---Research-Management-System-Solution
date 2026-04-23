<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassAssignment;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/teacher/dashboard', [ClassAssignment::class, 'teacherDashboard'])->name('teacher.dashboard');
Route::get('/teacher/classes', [ClassAssignment::class, 'teacherIndex'])->name('teacher.classes.index');
Route::get('/teacher/classes/{class}', [ClassAssignment::class, 'showClassDetails'])->name('teacher.classes.show');
Route::post('/teacher/classes/{class}/assign-group', [ClassAssignment::class, 'assignGroup'])->name('teacher.classes.assign-group');
Route::post('/teacher/classes/{class}/create-group', [ClassAssignment::class, 'createGroup'])->name('teacher.classes.create-group');
Route::post('/teacher/classes/{class}/group-students', [ClassAssignment::class, 'groupStudents'])->name('teacher.classes.group-students');
Route::post('/teacher/classes/{class}/groups/{group}/assign-task', [ClassAssignment::class, 'assignGroupTask'])->name('teacher.classes.groups.assign-task');

Route::get('/admin/dashboard', [ClassAssignment::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users', [ClassAssignment::class, 'adminUsers'])->name('admin.users');
Route::post('/admin/users/{role}/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
Route::get('/Adminclass', [ClassAssignment::class, 'index'])->name('adminclass.index');
Route::post('/Adminclass/assign', [ClassAssignment::class, 'store'])->name('adminclass.assign');
Route::post('/Adminclass/create', [ClassAssignment::class, 'storeClass'])->name('adminclass.storeClass');

Route::get('/user-management', [UserController::class, 'index'])->name('user-management.index');
Route::get('/user-management/create-teacher', [UserController::class, 'createTeacher'])->name('user-management.create-teacher');
Route::post('/user-management/store-teacher', [UserController::class, 'storeTeacher'])->name('user-management.store-teacher');
Route::get('/user-management/create-student', [UserController::class, 'createStudent'])->name('user-management.create-student');
Route::post('/user-management/store-student', [UserController::class, 'storeStudent'])->name('user-management.store-student');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/student/dashboard', [AuthController::class, 'studentDashboard'])->name('student.dashboard');
    
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/classes', [StudentController::class, 'classes'])->name('classes');
        Route::get('/classes/{class}', [StudentController::class, 'class'])->name('classes.show');
        Route::get('/classes/{class}/tasks/{task}', [StudentController::class, 'task'])->name('classes.task');
        Route::post('/tasks/{task}/submit', [StudentController::class, 'submitTask'])->name('tasks.submit');
    });
});

