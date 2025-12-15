<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
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



require __DIR__ . '/auth.php';
