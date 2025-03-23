<?php

namespace App\Http\Livewire;

use App\Models\Conversation;  
use Livewire\Component;

class PollingTest extends Component
{
    public $time;
    public $conversations;

    public function mount()
    {
        $this->time = now();
        $this->fetchConversations();
    }

    // Fetch the latest conversations along with the user and latest message
    public function fetchConversations()
    {
        $this->conversations = Conversation::with('user', 'latestMessage') // Eager load user and latest message
            ->orderBy('convoID', 'desc')
            ->take(10) // Fetch the latest 10 conversations
            ->get();
    }

    public function render()
    {
        $this->time = now(); // Update the time to show real-time polling
        $this->fetchConversations(); // Fetch the latest conversations on each render

        return view('livewire.polling-test'); // Pass the data to the Blade view
    }
}
