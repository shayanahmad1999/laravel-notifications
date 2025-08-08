# Laravel Project for Notification Setup

This README will guide you through setting up and running the Laravel project locally.

## Prerequisites

Ensure the following tools are installed on your system:
🔧 Tech Stack:

-   PHP >= 8.4
-   Laravel = 12
-   Composer
-   Node.js >= 24.x
-   NPM >= 8.x
-   MySQL or any supported database

## Installation & Setup

Follow the steps below to get started:

```bash
# Clone the repository
git clone https://github.com/shayanahmad1999/laravel-notifications.git
cd laravel-notifications

# Install PHP dependencies
composer install

# Initialize and install Node.js dependencies
npm install

# Build frontend assets
npm run build

# Run the development server (optional during setup)
npm run dev

# Copy and set up the environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate

# Setting up the .env file
After setting up the .env file, please ensure that you have
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="${APP_NAME}"

Also set up your databse
DB_CONNECTION=sqlite
DB_HOST=host
DB_PORT=port
DB_DATABASE=datbase_name
DB_USERNAME=datbase_username
DB_PASSWORD=datbase_password

# Run database migrations
php artisan migrate

# Run the development server
php artisan serve
npm run dev

```

# OR Install from scratch

```bash
# 🛠️ Step 1: Install Laravel
composer create-project laravel/laravel laravel-notification
cd laravel-notification

#Step 1.1: Install Breeze Authentication
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate

#📦 Step 2: Configure Mail in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="${APP_NAME}"

#🧪 Step 3: Test Email Configuration (Optional)
// --- routs/web.php ---//
Route::get('/notify', function () {
    $user = auth()->user();
    $user->notify(new NewMessageNotification('You have a new message!'));
    return redirect('/notifications');
});

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');


#✨ Step 4: Create a Notification
php artisan make:notification NewMessageNotification

#🧾 Step 5: Customize the Notification
NewMessageNotification.php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Message Notification')
            ->line($this->message)
            ->action('Check It Out', url('/notifications'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => url('/notifications'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
?>

#👥 Step 6: Trigger Notification After Registration (Example)
In App\Models\User, ensure it uses Notifiable:
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    // ...
}

#👥 Step 7: Set up the index file of notification in view
view/notification/index.blade.php

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>
    <div class="container">

        <div class="flex space-x-4 mb-4">
            <a href="{{ route('notifications.index') }}" class="btn">All</a>
            <a href="{{ route('notifications.unread') }}" class="btn">Unread</a>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="btn bg-green-500 text-white">Mark All as Read</button>
            </form>
        </div>

        @forelse($notifications as $notification)
            <div class="p-4 mb-2 rounded border {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }}">
                <p>{{ $notification->data['message'] }}</p>
                <small>{{ $notification->created_at->diffForHumans() }}</small>

                @if(!$notification->read_at)
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                    @csrf
                    <button class="ml-2 text-sm text-blue-600 underline">Mark as Read</button>
                </form>
                @endif
            </div>
        @empty
            <p>No notifications</p>
        @endforelse
    </div>
</x-app-layout>
@endsection

#✨ Step 8: Create a Notification Controller
php artisan make:controller NotificationController

# Step 9: Customize the Notification Controller
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
     public function index()
    {
        $notifications = Auth::user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications;
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('toast', 'Notification marked as read!');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('toast', 'All notifications marked as read!');
    }
}

# Run database migrations
php artisan migrate

# Run the development server
php artisan serve
npm run dev


```
