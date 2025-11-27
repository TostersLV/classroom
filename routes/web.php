<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PostsController;


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

    // store form submission
    Route::post('posts', [PostsController::class, 'store'])->name('posts.store');
});

Route::get('/posts/{post}', [PostsController::class, 'show'])->middleware(['auth', 'verified'])->name('posts.show');

require __DIR__.'/auth.php';
