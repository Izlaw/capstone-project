<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Events\OrderStatusUpdatedEvent;

class OrderStatus extends Component
{
    public $orderId;
    public $orderStatus;

    protected $listeners = [
        'OrderStatusUpdatedEvent' => 'refreshStatus',
        'orderStatusUpdated'      => 'refreshStatus',
    ];

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->orderStatus = $this->fetchOrderStatus();
    }

    public function refreshStatus($updatedOrderId, $newStatus = null)
    {
        Log::info('OrderStatus::refreshStatus', [
            'componentId' => $this->id,
            'orderId'     => $this->orderId,
            'updatedId'   => $updatedOrderId,
            'newStatus'   => $newStatus,
        ]);

        if ($this->orderId == $updatedOrderId) {
            $this->orderStatus = $newStatus ?? $this->fetchOrderStatus();
            $this->emitSelf('statusRefreshed');     // Notify this component
            $this->emitTo('order-list', 'refreshStatus'); // Ensure OrderList refreshes
        }
    }

    private function fetchOrderStatus()
    {
        $order = Order::find($this->orderId);
        return $order ? $order->orderStatus : 'Unknown';
    }

    public function updateOrderStatus($newStatus)
    {
        Log::info('updateOrderStatus called', [
            'order_id'   => $this->orderId,
            'new_status' => $newStatus,
        ]);

        $order = Order::find($this->orderId);
        if ($order) {
            // If the order is already completed, prevent any changes.
            if ($order->orderStatus === 'Completed') {
                return;
            }

            $oldStatus = $order->orderStatus;

            // If setting status to "Completed", update the dateReceived.
            if ($newStatus === 'Completed') {
                $order->dateReceived = now();
            }

            $order->orderStatus = $newStatus;
            $order->save();

            $this->orderStatus = $newStatus;

            // Dispatch the broadcast event
            event(new OrderStatusUpdatedEvent($order));

            // Emit local events for NotificationBell and other components
            $this->emit('orderStatusUpdated', $this->orderId, $newStatus, $oldStatus);
            $this->emitTo('notification-bell', 'orderStatusUpdated', $this->orderId, $newStatus, $oldStatus);

            Log::info('OrderStatusUpdatedEvent emitted', [
                'order_id'   => $this->orderId,
                'new_status' => $newStatus,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.order-status', ['orderStatus' => $this->orderStatus]);
    }
}
