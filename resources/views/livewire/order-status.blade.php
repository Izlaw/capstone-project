<div class="flex justify-center">
    <div class="min-h-[40px] flex items-center">
        @if(auth()->user()->isAdmin() || auth()->user()->isEmployee() && $orderStatus !== 'Completed')
        <select wire:change="updateOrderStatus($event.target.value)"
            class="px-4 py-2 rounded-full text-sm font-medium
                    @if ($orderStatus == 'Pending') bg-yellow-400 text-black
                    @elseif ($orderStatus == 'In Progress') bg-blue-500 text-white
                    @elseif ($orderStatus == 'Ready for Pickup') bg-green-500 text-white
                    @elseif ($orderStatus == 'Completed') bg-gray-500 text-white
                    @elseif ($orderStatus == 'Cancelled') bg-red-500 text-white
                    @else bg-gray-300 text-black
                    @endif">
            <option value="Pending" @if($orderStatus=='Pending' ) selected @endif>Pending</option>
            <option value="In Progress" @if($orderStatus=='In Progress' ) selected @endif>In Progress</option>
            <option value="Ready for Pickup" @if($orderStatus=='Ready for Pickup' ) selected @endif>Ready for Pickup</option>
            <option value="Completed" @if($orderStatus=='Completed' ) selected @endif>Completed</option>
            <option value="Cancelled" @if($orderStatus=='Cancelled' ) selected @endif>Cancelled</option>
        </select>
        @else
        <span class="px-4 py-2 rounded-full text-sm font-medium
                @if ($orderStatus == 'Pending') bg-yellow-400 text-black
                @elseif ($orderStatus == 'In Progress') bg-blue-500 text-white
                @elseif ($orderStatus == 'Ready for Pickup') bg-green-500 text-white
                @elseif ($orderStatus == 'Completed') bg-gray-500 text-white
                @elseif ($orderStatus == 'Cancelled') bg-red-500 text-white
                @else bg-gray-300 text-black
                @endif">
            {{ $orderStatus }}
        </span>
        @endif
    </div>
</div>