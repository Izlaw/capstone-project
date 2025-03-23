<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Log; // Add the Log facade for logging

class NewOrder extends Component
{
    public $ViewOrder;

    protected $listeners = [
        'OrderCreated' => 'fetchOrders',
    ];

    // The mount method is called when the component is initialized
    public function mount()
    {
        // Call fetchOrders method to load orders data initially
        $this->fetchOrders();
    }

    // Method to fetch orders from the database
    public function fetchOrders()
    {
        // Log to confirm that the OrderCreated event was detected and the method was called
        Log::info('OrderCreated event detected, fetching orders...');

        // Retrieve all orders with their related models (eager loading)
        $this->ViewOrder = Order::with(['collection', 'customOrder', 'uploadOrder', 'conversation'])->get();
    }

    // The render method is responsible for returning the view to be rendered
    public function render()
    {
        // Return the 'livewire.new-order' Blade view to display the orders
        return view('livewire.new-order');
    }
}
