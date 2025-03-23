@vite([
        'resources/js/app.js', 
        'resources/js/manageorder.js',
        'resources/css/app.css',  
        ])
    @include('layouts.header')
    
<form action="{{ route('adminOrder.update', $order->orderID) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Include form fields for order details, populated with $order values -->
    <button type="submit">Update Order</button>
</form>
