<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('🔔 Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-6">
                {{-- Header Buttons --}}
                <div class="flex flex-wrap items-center justify-between mb-6">
                    <div class="space-x-4 text-lg font-semibold text-gray-700">
                        <a href="{{ route('notifications.index') }}"
                            class="hover:underline {{ request()->routeIs('notifications.index') ? 'text-indigo-600' : '' }}">All</a>
                        <a href="{{ route('notifications.unread') }}"
                            class="hover:underline {{ request()->routeIs('notifications.unread') ? 'text-indigo-600' : '' }}">Unread</a>
                        <a href="{{ route('notifications.readList') }}"
                            class="hover:underline {{ request()->routeIs('notifications.readList') ? 'text-indigo-600' : '' }}">Read</a>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-3 sm:mt-0">
                        <form method="POST" action="{{ route('notifications.readAll') }}">
                            @csrf
                            <button
                                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded transition">
                                ✅ Mark All as Read
                            </button>
                        </form>

                        <form method="POST" action="{{ route('notifications.unreadAll') }}">
                            @csrf
                            <button
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded transition">
                                🔁 Mark All as Unread
                            </button>
                        </form>

                        <form method="POST" action="{{ route('notifications.send') }}">
                            @csrf
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition">
                                📤 Send Notification
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Notifications List --}}
                <div class="space-y-4">
                    @forelse($notifications as $notification)
                        <div
                            class="relative bg-{{ $notification->read_at ? 'gray' : 'blue' }}-100 hover:bg-{{ $notification->read_at ? 'gray' : 'blue' }}-200 transition p-5 rounded-lg shadow-sm">
                            <p class="text-gray-800 text-sm font-medium mb-2">
                                {{ $notification->data['message'] }}
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>{{ $notification->created_at->diffForHumans() }}</span>

                                {{-- Actions --}}
                                @if (!$notification->read_at)
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}"
                                        class="inline">
                                        @csrf
                                        <button class="text-blue-600 hover:underline">📬 Mark as Read</button>
                                    </form>
                                @else
                                    <form method="POST"
                                        action="{{ route('notifications.unreadOne', $notification->id) }}"
                                        class="inline">
                                        @csrf
                                        <button class="text-yellow-600 hover:underline">🔁 Mark as Unread</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-6">No notifications to display.</p>
                    @endforelse
                </div>

                {{-- Toast Message --}}
                @if (session('toast'))
                    <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                        {{ session('toast') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
