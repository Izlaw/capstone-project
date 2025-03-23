<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderList extends Component
{
    public $orders; // All orders passed to the component.

    protected $listeners = [
        'refreshStatus' => 'reloadOrders',
        'orderStatusUpdated' => 'handleOrderStatusUpdated',
    ];

    public function mount()
    {
        // Eager load the necessary relationships
        $this->orders = Order::with(['collection', 'customOrder', 'uploadOrder'])
            ->orderBy('orderID', 'desc')
            ->get();
    }

    public function reloadOrders()
    {
        Log::info("Reloading *CUSTOMER* order list", ['session_id' => session()->getId()]);
        $this->orders = Order::with(['collection', 'customOrder', 'uploadOrder'])
            ->orderBy('orderID', 'desc')
            ->get();
    }

    public function handleOrderStatusUpdated($orderId, $newStatus = null, $oldStatus = null)
    {
        Log::info("handleOrderStatusUpdated called", [
            'order_id'   => $orderId,
            'new_status' => $newStatus ?? 'Unknown',
            'old_status' => $oldStatus ?? 'N/A'
        ]);

        // Optionally, reload the orders to reflect the updated status in the table:
        $this->reloadOrders();
        Log::info('reloadOrders fired');
    }

    public function render()
    {
        return view('livewire.order-list', ['orders' => $this->orders]);
    }
}
