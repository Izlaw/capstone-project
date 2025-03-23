<div>

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>

    @if(auth()->user()->isAdmin() || auth()->user()->isEmployee())
    <h1 class="text-4xl text-highlight font-extrabold mb-6">Manage Orders</h1>
    <div class="w-full h-[calc(100vh-13rem)] overflow-y-auto">
        <table class="table-auto w-full rounded-lg shadow-xl bg-white text-center">
            <thead class="sticky top-0 bg-primary text-highlight z-10">
                <tr class="bg-primary text-highlight">
                    <th class="px-6 py-4 text-sm text-white">CUSTOMER NAME</th>
                    <th class="px-6 py-4 text-sm text-white">SIZE</th>
                    <th class="px-6 py-4 text-sm text-white">TOTAL QUANTITY</th>
                    <th class="px-6 py-4 text-sm text-white">TOTAL PRICE</th>
                    <th class="px-6 py-4 text-sm text-white">DATE ORDERED</th>
                    <th class="px-6 py-4 text-sm text-white">DATE RECEIVED</th>
                    <th class="px-6 py-4 text-sm text-white">STATUS</th>
                    <th class="px-6 py-4 text-sm text-white">ORDER TYPE</th>
                    <th class="px-6 py-4 text-sm text-white">CONVERSATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="group border-b border-gray-300 text-black hover:bg-accent transition-all duration-200 hover:text-white">
                    <td class="px-6 py-4">
                        {{ $order->user->first_name ?? 'N/A' }} {{ $order->user->last_name ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        @if ($order->customID)
                        @if($order->customOrder->sizes->isNotEmpty())
                        @foreach($order->customOrder->sizes as $size)
                        <span>{{ $size->sizeName }}: {{ $size->pivot->quantity }}</span>
                        @endforeach
                        @else
                        N/A
                        @endif
                        @elseif ($order->upID)
                        @if($order->uploadOrder->sizes->isNotEmpty())
                        @foreach($order->uploadOrder->sizes as $size)
                        <span>{{ $size->sizeName }}: {{ $size->pivot->quantity }}</span>
                        @endforeach
                        @else
                        N/A
                        @endif
                        @elseif ($order->collectID)
                        @if($order->collections->isNotEmpty())
                        @foreach($order->collections as $collection)
                        <span>{{ $collection->pivot->sizeName }}: {{ $collection->pivot->quantity }}</span>
                        @endforeach
                        @else
                        N/A
                        @endif
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="px-6 py-4">{{ $order->orderQuantity }}</td>
                    <td class="px-6 py-4">₱{{ number_format($order->orderTotal, 2) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->dateOrder)->format('M, j Y') }}</td>
                    <td class="px-6 py-4">
                        {{ $order->dateReceived ? \Carbon\Carbon::parse($order->dateReceived)->format('M, j Y') : 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        <livewire:order-status
                            :orderId="$order->orderID"
                            wire:key="status-{{ $order->orderID }}-{{ $order->orderStatus }}" />
                    </td>

                    <td class="px-6 py-4">
                        @if ($order->collectID)
                        Collection Order
                        @elseif ($order->customID)
                        Custom Order
                        @elseif ($order->upID)
                        Upload Order
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center flex items-center justify-center" wire:ignore>
                        @if($order->convoID)
                        <a href="{{ route('chat', ['convoID' => $order->convoID]) }}"
                            class="text-black transition-colors mt-3 group-hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 transition-transform duration-300 ease-in-out group-hover:scale-125 group-hover:rotate-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h8M8 14h6M21 12c0 4.418-3.582 8-8 8H5a4 4 0 01-4-4V5a4 4 0 014-4h12c4.418 0 8 3.582 8 8z" />
                            </svg>
                        </a>
                        @else
                        <span class="text-black transition-colors group-hover:text-white">No Conversation</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @elseif(auth()->user()->isCustomer())

    <div wire:poll.1s="reloadOrders">
        <h1 class="text-4xl text-highlight font-extrabold mb-6">Your Orders</h1>
        <div class="w-full max-w-7xl h-[calc(100vh-13rem)] overflow-y-auto">
            <table class="table-auto w-full text-center rounded-lg shadow-xl bg-white">
                <thead class="sticky top-0 bg-primary text-highlight z-10">
                    <tr class="bg-primary text-highlight">
                        <th class="px-6 py-4 text-sm text-white">ORDER NAME</th>
                        <th class="px-6 py-4 text-sm text-white">TOTAL PRICE</th>
                        <th class="px-6 py-4 text-sm text-white text-center">STATUS</th>
                        <th class="px-6 py-4 text-sm text-white">DATE ORDERED</th>
                        <th class="px-6 py-4 text-sm text-white">DATE RECEIVED</th>
                        <th class="px-6 py-4 text-sm text-white">ORDER TYPE</th>
                        <th class="px-6 py-4 text-sm text-white">CONVERSATION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="group border-b border-gray-300 text-white hover:bg-white transition-all duration-200 hover:text-black bg-accent"
                        onclick="window.location.href='{{ route('orderdetails', $order->orderID) }}'">

                        <td class="px-6 py-4">
                            @if ($order->collectID)
                            {{ ucfirst($order->collection->collectName ?? 'N/A') }}
                            @elseif ($order->customID)
                            T-Shirt
                            @elseif ($order->upID)
                            {{ ucfirst($order->uploadOrder->upName ?? 'N/A') }}
                            @else
                            N/A
                            @endif
                        </td>

                        <td class="px-6 py-4">₱{{ number_format($order->orderTotal, 2) }}</td>

                        <td class="px-6 py-4">
                            <livewire:order-status
                                :orderId="$order->orderID"
                                wire:key="status-{{ $order->orderID }}-{{ $order->orderStatus }}" />
                        </td>

                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->dateOrder)->format('M, j Y') }}</td>

                        <td class="px-6 py-4">
                            {{ $order->dateReceived ? \Carbon\Carbon::parse($order->dateReceived)->format('M, j Y') : 'N/A' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($order->collectID)
                            Collection
                            @elseif ($order->customID)
                            Custom Order
                            @elseif ($order->upID)
                            Uploaded Order
                            @else
                            Other
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center flex items-center justify-center" wire:ignore>
                            @if($order->convoID)
                            <a href="{{ route('chat', ['convoID' => $order->convoID]) }}"
                                class="text-white group-hover:text-black mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 group-hover:scale-125 group-hover:rotate-3 transition-all duration-300 ease-in-out"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h8M8 14h6M21 12c0 4.418-3.582 8-8 8H5a4 4 0 01-4-4V5a4 4 0 014-4h12c4.418 0 8 3.582 8 8z" />
                                </svg>
                            </a>
                            @else
                            <span class="text-gray-500 group-hover:text-black">No Conversation</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>