<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\Meeting;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/operations', fn() => view('operations'))->name('operations');
Route::get('/media', fn() => view('media'))->name('media');

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Meetings
    Route::resource('meetings', MeetingController::class);

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});

/*
|---------------------------------------------------------
| Customer Orders (لو انت محتاجها للـ customer فقط)
|---------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // لو لسه في navigation بتنده على orders.my-orders
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
});

/*
|---------------------------------------------------------
| Admin Panel (Admin + SuperAdmin)
|---------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin,admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |==========================
        | Orders (Admin Panel)
        |==========================
        */
        Route::get('/orders', [AdminController::class, 'orderIndex'])->name('orders.index');
        Route::get('/orders/create', [AdminController::class, 'orderCreate'])->name('orders.create');
        Route::post('/orders', [AdminController::class, 'orderstore'])->name('orders.store');

        Route::get('/orders/{order}', [AdminController::class, 'ordershow'])->name('orders.show');
        Route::get('/orders/{order}/edit', [AdminController::class, 'orderEdit'])->name('orders.edit');
        Route::delete('/orders/{order}', [AdminController::class, 'orderdestroy'])->name('orders.destroy');

        /*
        | Customers CRUD (Admin + SuperAdmin)
        */
        Route::get('/customers', [AdminController::class, 'customerIndex'])->name('customer-index');
        Route::get('/customers/create', [AdminController::class, 'customerCreate'])->name('customer-create');
        Route::post('/customers', [AdminController::class, 'customerStore'])->name('customer-store');
        Route::get('/customers/{user}', [AdminController::class, 'customerShow'])->name('customer-show');
        Route::get('/customers/{user}/edit', [AdminController::class, 'customerEdit'])->name('customer-edit');
        Route::put('/customers/{user}', [AdminController::class, 'customerUpdate'])->name('customer-update');
        Route::delete('/customers/{user}', [AdminController::class, 'customerDestroy'])->name('customer-destroy');
    });

/*
|---------------------------------------------------------
| SuperAdmin فقط: Admins CRUD
|---------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin/admins', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/admins/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/admins', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/admins/{user}', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/admins/{user}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/admins/{user}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/admins/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');

        Route::delete('/admin/admins/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');
        Route::get('/admin/meetings', [MeetingController::class, 'adminIndex'])->name('admin.meetings.index');
        Route::patch('/admin/meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('admin.meetings.update-status');

});

require __DIR__ . '/auth.php';
