<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;
use Carbon\Carbon;

class NotificationBell extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $userRole;

    protected $listeners = [
        'conversationCreated' => 'handleConversationCreated',
        'loadNotifications'   => 'loadNotificationsFromStorage',
        'orderStatusUpdated'  => 'handleOrderStatusUpdated', // existing listener
        'refreshStatus'       => 'handleRefreshStatus',      // new listener for broadcast events
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->userRole = Auth::user()->role;
            Log::info('User authenticated in NotificationBell mount', [
                'user_id' => Auth::id(),
                'role'    => $this->userRole,
            ]);
            $this->loadOrders();
        } else {
            $this->userRole = 'guest';
        }
    }

    private function getLatestOrders()
    {
        return Order::with('user')
            ->orderBy('orderID', 'desc')
            ->take(10)
            ->get();
    }

    private function formatAdminNotifications($orders)
    {
        $notifications = [];
        foreach ($orders as $order) {
            $userName = $order->user->fullCustomerName();
            $orderID  = $order->orderID;
            $notifications[] = [
                'content' => 'New order from: ' . $userName . ' (Order ID: ' . $orderID . ')',
                'orderID' => $order->orderID,
                'type'    => 'admin',
            ];
        }
        return $notifications;
    }

    private function formatCustomerNotifications($orders)
    {
        $notifications = [];
        foreach ($orders as $order) {
            $orderDetails = [
                'type' => 'custom', // Default type
                'name' => 'T-Shirt' // Default name
            ];

            // Determine order type and get correct name
            if ($order->collectID && $order->collection) {
                $orderDetails['type'] = 'collection';
                $orderDetails['name'] = $order->collection->collectName;
            } elseif ($order->upID && $order->uploadOrder) {
                $orderDetails['type'] = 'upload';
                $orderDetails['name'] = $order->uploadOrder->upName;
            } elseif ($order->customID && $order->customOrder) {
                $orderDetails['type'] = 'custom';
                $orderDetails['name'] = 'T-Shirt'; // Custom orders are always T-Shirts
            }

            $notifications[] = [
                'status' => $order->orderStatus,
                'orderID' => $order->orderID,
                'type' => 'customer',
                'orderDetails' => $orderDetails
            ];
        }
        return $notifications;
    }

    public function loadOrders()
    {
        $orders = $this->getLatestOrders();
        if ($this->userRole === 'admin' || $this->userRole === 'employee') {
            $this->notifications = $this->formatAdminNotifications($orders);
        } elseif ($this->userRole === 'customer') {
            // Retrieve the last time notifications were marked as read from session
            $lastRead = session()->get('notifications_last_read');
            $this->notifications = $this->formatCustomerNotifications($orders);
        }
        $this->updateUnreadCount();
    }

    private function updateUnreadCount()
    {
        if ($this->userRole === 'admin' || $this->userRole === 'employee') {
            // Count only admin notifications
            $this->unreadCount = count(array_filter($this->notifications, function ($notification) {
                return $notification['type'] === 'admin';
            }));
        } elseif ($this->userRole === 'customer') {
            // Count only customer notifications
            $this->unreadCount = count(array_filter($this->notifications, function ($notification) {
                return $notification['type'] === 'customer';
            }));
        } else {
            $this->unreadCount = 0;
        }

        $this->emit('notificationUpdated', $this->unreadCount);
    }

    public function handleOrderStatusUpdated($orderId, $newStatus, $oldStatus = null)
    {
        Log::info('handleOrderStatusUpdated called', [
            'order_id'   => $orderId,
            'new_status' => $newStatus,
            'old_status' => $oldStatus,
        ]);

        $order = Order::with(['collection', 'uploadOrder', 'customOrder'])->find($orderId);
        if (!$order) {
            Log::error('Order not found for ID: ' . $orderId);
            return;
        }


        // Skip further processing if user is admin/employee and this is a customer notification
        if (($this->userRole === 'admin' || $this->userRole === 'employee') && $this->userRole !== 'customer') {
            Log::info('Skipping customer notification for admin/employee user');
            return;
        }

        $orderDetails = [
            'type' => 'custom',
            'name' => 'T-Shirt'
        ];

        if ($order->collectID && $order->collection) {
            $orderDetails['type'] = 'collection';
            $orderDetails['name'] = $order->collection->collectName;
        } elseif ($order->upID && $order->uploadOrder) {
            $orderDetails['type'] = 'upload';
            $orderDetails['name'] = $order->uploadOrder->upName;
        }

        $newNotification = [
            'status' => $newStatus,
            'orderID' => $orderId,
            'type' => 'customer',
            'orderDetails' => $orderDetails
        ];

        // Check if a notification for this order already exists
        $existingNotificationIndex = $this->findNotificationIndexByOrderId($orderId);
        $newNotification = [
            'status'  => $newStatus,
            'orderID' => $orderId,
            'type'    => 'customer',
        ];

        if ($existingNotificationIndex !== false) {
            // Update the existing notification
            $this->notifications[$existingNotificationIndex] = $newNotification;
        } else {
            // Add a new notification
            array_unshift($this->notifications, $newNotification);
        }

        $this->updateUnreadCount();

        // Attempt to send email for order status update
        Log::info('Attempting to send email for order status update', [
            'order_id'   => $orderId,
            'user_email' => $order->user->email,
            'new_status' => $newStatus,
            'old_status' => $oldStatus,
        ]);
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $newStatus, $oldStatus));
            Log::info('Email sent successfully to ' . $order->user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send email for order status update', [
                'error_message' => $e->getMessage(),
                'order_id'      => $orderId,
                'user_email'    => $order->user->email,
            ]);
        }
    }

    // Helper method to find notification by order ID
    private function findNotificationIndexByOrderId($orderId)
    {
        foreach ($this->notifications as $index => $notification) {
            if (isset($notification['orderID']) && $notification['orderID'] == $orderId) {
                return $index;
            }
        }
        return false;
    }

    // New method: update notifications when a broadcast event is received via Livewire
    public function handleRefreshStatus($orderId, $status)
    {
        Log::info('handleRefreshStatus called via broadcast', [
            'order_id' => $orderId,
            'status'   => $status,
        ]);

        // Only process customer notifications if user is a customer
        if ($this->userRole !== 'customer') {
            Log::info('Skipping customer notification for non-customer user');
            return;
        }

        $order = Order::find($orderId);
        if ($order) {
            $newNotification = [
                'status'  => $status,
                'orderID' => $orderId,
                'type'    => 'customer',
            ];

            // Check if a notification for this order already exists
            $existingNotificationIndex = $this->findNotificationIndexByOrderId($orderId);

            if ($existingNotificationIndex !== false) {
                // Update the existing notification
                $this->notifications[$existingNotificationIndex] = $newNotification;
            } else {
                // Add a new notification
                array_unshift($this->notifications, $newNotification);
            }

            $this->updateUnreadCount();
        }
    }

    public function handleConversationCreated($event)
    {
        // Only process admin notifications if user is admin/employee
        if ($this->userRole !== 'admin' && $this->userRole !== 'employee') {
            Log::info('Skipping admin notification for non-admin user');
            return;
        }

        $newNotification = [
            'content' => 'A new conversation has been created. Order ID: ' . $event->orderID,
            'orderID' => $event->orderID,
            'type'    => 'admin',
        ];

        // For conversations, check if admin notification for this order already exists
        $existingNotificationIndex = $this->findNotificationIndexByOrderId($event->orderID);

        if ($existingNotificationIndex !== false) {
            // Update the existing notification
            $this->notifications[$existingNotificationIndex] = $newNotification;
        } else {
            // Add a new notification
            array_unshift($this->notifications, $newNotification);
        }

        $this->updateUnreadCount();
    }

    public function markAsRead()
    {
        Log::info('markAsRead() function triggered.');
        session()->flash('message', 'Marked as read!');
        // Record the current time as the last time notifications were read.
        session()->put('notifications_last_read', now());
        $this->notifications = [];
        $this->unreadCount = 0;
        $this->emit('clearNotifications');
    }

    public function loadNotificationsFromStorage()
    {
        $this->emit('loadFromStorage');
    }

    public function render()
    {
        Log::info('Rendering NotificationBell component', [
            'userRole'      => $this->userRole,
            'notifications' => $this->notifications,
            'unreadCount'   => $this->unreadCount
        ]);
        return view('livewire.notification-bell', [
            'userRole' => $this->userRole,
        ]);
    }
}
