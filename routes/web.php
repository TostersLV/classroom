<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SubmitTaskController;
use App\Http\Controllers\TaskCommentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PostsController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::post('/posts/{post}/generate-code', [PostsController::class, 'generateCode'])->name('posts.generate_code');
    Route::post('posts', [PostsController::class, 'store'])->name('posts.store');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware(['auth', 'verified']);
    // Teacher: list submissions for a task
    Route::get('/posts/{post}/tasks/{task}/submissions', [SubmitTaskController::class, 'index'])
    ->name('posts.tasks.submissions.index')
    ->middleware('auth');

    // Teacher: show grade form for a submission
    Route::get('/posts/{post}/tasks/{task}/submissions/{submission}/grade', [SubmitTaskController::class, 'gradeForm'])
    ->name('posts.tasks.submissions.grade')
    ->middleware('auth');

    // Teacher: save grade for a submission
    Route::post('/posts/{post}/tasks/{task}/submissions/{submission}/grade', [SubmitTaskController::class, 'updateGrade'])
    ->name('posts.tasks.submissions.update')
    ->middleware('auth');
});


Route::get('/posts/{post}', [PostsController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store')
    ->middleware(['auth']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(['auth', 'verified']);
Route::get('/posts/{post}/tasks/{task}', [TaskController::class, 'show'])
    ->name('posts.tasks.show')
    ->middleware(['auth']); // adjust middleware as needed

Route::post('/classroom/join', [ClassroomController::class, 'join'])
    ->name('classroom.join')
    ->middleware('auth');

// Student submit / unsubmit routes
Route::post('posts/{post}/tasks/{task}/submissions', [SubmitTaskController::class, 'store'])
    ->middleware('auth')
    ->name('posts.tasks.submissions.store');

Route::post('posts/{post}/tasks/{task}/submissions/unsubmit', [SubmitTaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('posts.tasks.submissions.unsubmit');

// Teacher: list submissions, grade form, and grade save
Route::get('posts/{post}/tasks/{task}/submissions', [SubmitTaskController::class, 'index'])
    ->middleware('auth')
    ->name('posts.tasks.submissions.index');

Route::get('posts/{post}/tasks/{task}/submissions/{submission}/grade', [SubmitTaskController::class, 'gradeForm'])
    ->middleware('auth')
    ->name('posts.tasks.submissions.grade');

Route::post('posts/{post}/tasks/{task}/submissions/{submission}/grade', [SubmitTaskController::class, 'updateGrade'])
    ->middleware('auth')
    ->name('posts.tasks.submissions.update');

Route::post('posts/{post}/tasks/{task}/comments', [TaskCommentController::class, 'store'])
    ->middleware('auth')
    ->name('posts.tasks.comments.store');

require __DIR__.'/auth.php';
