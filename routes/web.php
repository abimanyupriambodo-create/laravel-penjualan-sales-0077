
<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return 'Halaman Utama / Katalog (shop.index)';
})->name('shop.index');

// Login dan register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return 'Halo Admin! Login berhasil.';
    })->name('admin.dashboard');

    // Kategori
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Produk
    Route::resource('/admin/products', AdminProductController::class)
        ->except('show')
        ->names('admin.products');
});
