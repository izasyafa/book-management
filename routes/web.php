<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookImportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Routing Dashboard
    Route::get('/dashboard', [BookController::class, 'tableWriter'])->name('dashboard');

    // Routing Book
    Route::get('/book', [BookController::class, 'index'])->name('book.index');
    Route::get('/books/data', [BookController::class, 'getBooks'])->name('getBooks');
    Route::post('/book', [BookController::class, 'store'])->name('book.store');
    Route::post('/getBookById',[BookController::class, 'edit'])->name('book.edit');
    Route::put('/book/update', [BookController::class, 'update'])->name('book.update');
    Route::delete('/book/{id}', [BookController::class, 'destroy'])->name('book.destroy');

    // Routing Category
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/data', [CategoryController::class, 'getCategories'])->name('getCategories');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/getcategoryById',[CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Routing Publisher
    Route::get('/publisher', [PublisherController::class, 'index'])->name('publisher.index');
    Route::get('/publishers/data', [PublisherController::class, 'getPublishers'])->name('getPublishers');
    Route::post('/publisher', [PublisherController::class, 'store'])->name('publisher.store');
    Route::post('/getpublisherById',[PublisherController::class, 'edit'])->name('publisher.edit');
    Route::put('/publisher/update', [PublisherController::class, 'update'])->name('publisher.update');
    Route::delete('/publisher/{id}', [PublisherController::class, 'destroy'])->name('publisher.destroy');

    // Routing User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/data', [UserController::class, 'getUsers'])->name('getUsers');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::post('/getuserById',[UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/roles/data', [RoleController::class, 'getRoles'])->name('getRoles');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::post('/getroleById',[RoleController::class, 'edit'])->name('role.edit');
    Route::post('/role/update', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('/permissions/data', [PermissionController::class, 'getPermissions'])->name('getPermissions');
    Route::post('/permission', [PermissionController::class, 'store'])->name('permission.store');
    Route::post('/getpermissionById',[PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('/permission/update', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');

    Route::post('/importBook', [BookImportController::class, 'import'])->name('import.book');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
