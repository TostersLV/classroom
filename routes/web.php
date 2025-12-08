<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClassroomController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PostsController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Classroom join - via POST only (for dashboard form)
    Route::post('/classrooms/join', [ClassroomController::class, 'join'])->name('classrooms.join');
});

// Classroom routes for teachers
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create');
    Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
    Route::get('/classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit');
    Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
    Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');
});

// Show classroom (students and teachers)
Route::middleware('auth')->group(function () {
    Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');
});



// Protected group for Admin access only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
});

Route::middleware(['auth','role:teacher'])->group(function () {
    // show form
    Route::get('/posts/create', [PostsController::class, 'create'])->name('posts.create');
    Route::get('/posts/{post}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
   

    // store form submission
    Route::post('posts', [PostsController::class, 'store'])->name('posts.store');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware(['auth', 'verified']);
});


Route::get('/posts/{post}', [PostsController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store')
    ->middleware(['auth']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(['auth', 'verified']);
Route::get('/posts/{post}/tasks/{task}', [TaskController::class, 'show'])
    ->name('posts.tasks.show')
    ->middleware(['auth']); // adjust middleware as needed

require __DIR__.'/auth.php';
