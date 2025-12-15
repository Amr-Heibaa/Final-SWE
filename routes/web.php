<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\Meeting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');})->name('home');

Route::get('/about', function () {
    return view('about');})->name('about');

Route::get('/operations',function(){
    return view('operations');
})->name('operations');

Route::get('/media',function(){
    return view('media');
})->name('media');










// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('meetings', MeetingController::class);



    Route::resource('orders', OrderController::class);

    Route::get('/my-orders', [OrderController::class, 'myOrders'])
    ->name('orders.my-orders')
    ->middleware('auth');


    // Additional order routes
    Route::get('/orders/customer/{customer}', [OrderController::class, 'byCustomer'])->name('orders.by-customer');
    Route::get('/orders/meeting/{meeting}', [OrderController::class, 'byMeeting'])->name('orders.by-meeting');
    Route::get('/orders/statistics', [OrderController::class, 'statistics'])->name('orders.statistics');
    Route::post('/orders/{order}/phase', [OrderController::class, 'updatePhase'])->name('orders.update-phase');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'myOrder'])->name('orders.my-order');
});
Route::middleware(['auth'])->get('/dashboard', function () {
    $meetings = Meeting::where('customer_id', auth()->id())
        ->latest('scheduled_date')
        ->paginate(10);

    return view('dashboard', compact('meetings'));
})->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'super_admin'])->group(function () {
    // Admin Management Routes
    Route::get('/admin/admins', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/admins/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/admins', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/admins/{user}', [AdminController::class, 'show'])->name('admin.show');
    Route::get('/admin/admins/{user}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/admins/{user}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/admins/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// Super Admin & Admin Routes (Customer Management)
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/customers', [AdminController::class, 'customerIndex'])->name('admin.customer-index');
    Route::get('/admin/customers/create', [AdminController::class, 'customerCreate'])->name('admin.customer-create');
    Route::post('/admin/customers', [AdminController::class, 'customerStore'])->name('admin.customer-store');
    Route::get('/admin/customers/{user}', [AdminController::class, 'customerShow'])->name('admin.customer-show');
    Route::get('/admin/customers/{user}/edit', [AdminController::class, 'customerEdit'])->name('admin.customer-edit');
    Route::put('/admin/customers/{user}', [AdminController::class, 'customerUpdate'])->name('admin.customer-update');
    Route::delete('/admin/customers/{user}', [AdminController::class, 'customerDestroy'])->name('admin.customer-destroy');
});



require __DIR__ . '/auth.php';
