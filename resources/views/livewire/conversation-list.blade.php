<div wire:poll.1s> <!-- Poll every 1 second -->
    <ul>
        @foreach($conversations as $conversation)
        @if($conversation->user) <!-- Check if user exists -->
        <li class="mb-4" data-conversation-id="{{ $conversation->convoID }}">
            <!-- Pass the correct 'convoID' parameter -->
            <a href="{{ route('assistcustomer.show', ['convoID' => $conversation->convoID]) }}" class="block p-4 bg-secondary rounded-lg shadow">
                <p class="font-semibold">{{ $conversation->user->fullCustomerName() }}</p>
                <p>{{ $conversation->latestMessage ? $conversation->latestMessage->messContent : 'No messages yet' }}</p>
            </a>
        </li>
        @else
        <li class="mb-4">
            <p class="text-red-500">No user found for this conversation</p>
        </li>
        @endif
        @endforeach
    </ul>
</div>