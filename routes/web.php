<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/notify', function () {
    $user = auth()->user();
    $user->notify(new NewMessageNotification('You have a new message!'));
    return redirect('/notifications');
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::get('/notifications/read', [NotificationController::class, 'read'])->name('notifications.readList');

    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/unread/{id}', [NotificationController::class, 'markAsUnread'])->name('notifications.unreadOne');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::post('/notifications/unread-all', [NotificationController::class, 'markAllAsUnread'])->name('notifications.unreadAll');

    Route::post('/notifications/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');
});


require __DIR__ . '/auth.php';
