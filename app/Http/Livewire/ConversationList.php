<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Conversation;

class ConversationList extends Component
{
    public $conversations;

    protected $listeners = ['echo:conversations,ConversationCreated' => '$refresh'];

    public function mount()
    {
        $this->conversations = Conversation::with('user', 'latestMessage')->get();
    }

    public function render()
    {
        return view('livewire.conversation-list');
    }
}
