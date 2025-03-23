<div
    x-data="{ notificationCount: {{ $unreadCount }} }"
    x-init="
        window.addEventListener('notificationUpdated', (event) => {
            notificationCount = event.detail;
        });
        window.addEventListener('notificationsCleared', () => {
            notificationCount = 0;
        });
        window.addEventListener('notificationLoaded', (event) => {
            notificationCount = event.detail;
        });
    ">
    <!-- Notification Bell -->
    <div class="relative">
        <button id="notificationBell" class="notification-bell p-2 rounded-full bg-white shadow-md transition-all duration-300 hover:bg-primary hover:text-white focus:outline-none relative">
            <!-- Bell Icon with animation -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="notificationCount > 0 ? 'animate-pulse' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
            </svg>
            <!-- Notification Badge -->
            <span
                id="notificationBadge"
                class="notification-badge absolute top-12 right-1 bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1 transform translate-x-1/2 -translate-y-1/2"
                x-text="notificationCount > 0 ? notificationCount : ''"
                x-bind:class="notificationCount > 0 ? 'block' : 'hidden'"></span>
        </button>
    </div>

    <!-- Notification Dropdown -->
    <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-md p-2 hidden z-50 opacity-0 scale-95 transform transition-all duration-300 ease-out">
        @if(count($notifications) > 0)
        @if($userRole === 'admin' || $userRole === 'employee')
        @foreach($notifications as $notification)
        @if($notification['type'] === 'admin')
        @php
        // Set $status to the notification's status if available, otherwise a default value
        $status = $notification['status'] ?? 'default';
        @endphp
        <a href="{{ route('adminOrder') }}" class="block">
            <div
                class="
                            notification-item
                            flex items-start p-4 mb-2
                            rounded-lg
                            border-l-4
                            transition-colors
                            hover:bg-gray-50
                            cursor-pointer
                            @if($status === 'Pending') border-yellow-400 bg-yellow-50
                            @elseif($status === 'In Progress') border-blue-400 bg-blue-50
                            @elseif($status === 'Ready for Pickup') border-green-500 bg-green-50
                            @elseif($status === 'Completed') border-gray-500 bg-gray-50
                            @elseif($status === 'Cancelled') border-red-500 bg-red-50
                            @else border-gray-300 bg-gray-50
                            @endif
                        ">
                <!-- Optional Icon -->
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>
                </div>
                <!-- Notification Text -->
                <div>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ $notification['content'] }}
                    </p>
                </div>
            </div>
        </a>
        @endif
        @endforeach

        @elseif($userRole === 'customer')
        @foreach($notifications as $notification)
        @if($notification['type'] === 'customer')
        @php
        $status = $notification['status'] ?? 'default';
        @endphp
        <a href="{{ route('orderdetails', $notification['orderID']) }}" class="block">
            <div
                class="
                            notification-item
                            flex items-start p-4 mb-2
                            rounded-lg
                            border-l-4
                            transition-colors
                            hover:bg-gray-50
                            cursor-pointer
                            @if($status === 'Pending') border-yellow-400 bg-yellow-50
                            @elseif($status === 'In Progress') border-blue-400 bg-blue-50
                            @elseif($status === 'Ready for Pickup') border-green-500 bg-green-50
                            @elseif($status === 'Completed') border-gray-500 bg-gray-50
                            @elseif($status === 'Cancelled') border-red-500 bg-red-50
                            @else border-gray-300 bg-gray-50
                            @endif
                        ">
                <!-- Optional Icon -->
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-6 w-6 
                                    @if($status === 'Pending') text-yellow-500
                                    @elseif($status === 'In Progress') text-blue-500
                                    @elseif($status === 'Ready for Pickup') text-green-500
                                    @elseif($status === 'Completed') text-gray-600
                                    @elseif($status === 'Cancelled') text-red-500
                                    @else text-gray-400
                                    @endif
                                    " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>
                </div>
                <!-- Notification Text -->
                <div>
                    <p class="text-sm font-semibold text-gray-700">
                        Your
                        @if(isset($notification['orderDetails']) && $notification['orderDetails']['type'] === 'collection')
                        {{ $notification['orderDetails']['name'] }}
                        @elseif(isset($notification['orderDetails']) && $notification['orderDetails']['type'] === 'upload')
                        {{ $notification['orderDetails']['name'] }}
                        @else
                        T-Shirt
                        @endif
                        order
                        <span class="font-bold">#{{ $notification['orderID'] }}</span>
                        status changed to:
                        <span
                            class="
                                        inline-block
                                        ml-1
                                        py-0.5 px-2
                                        rounded
                                        text-sm
                                        @if($status === 'Pending') bg-yellow-400 text-black
                                        @elseif($status === 'In Progress') bg-blue-500 text-white
                                        @elseif($status === 'Ready for Pickup') bg-green-500 text-white
                                        @elseif($status === 'Completed') bg-gray-500 text-white
                                        @elseif($status === 'Cancelled') bg-red-500 text-white
                                        @else bg-gray-300 text-black
                                        @endif
                                        ">
                            {{ $status }}
                        </span>
                    </p>
                </div>
            </div>
        </a>
        @endif
        @endforeach
        @else
        <div class="notification-item p-3 text-center text-gray-500">
            You don't have any new notifications
        </div>
        @endif
        @else
        <div class="notification-item p-3 text-center text-gray-500">
            No new notifications
        </div>
        @endif
    </div>
</div>