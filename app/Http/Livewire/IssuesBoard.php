<?php

namespace App\Http\Livewire;

use Livewire\Component;

class IssuesBoard extends Component
{
    public $boards;
    public $tasks;

    public function mount()
    {
        $this->boards = [
            'Todo',
            'In Progress',
            'Review',
            'Done'
        ];
        $this->tasks = json_encode("{
                    name: '',
                    boardName: '',
                    date: new Date()
                }");
    }

    public function render()
    {
        return view('livewire.issues-board');
    }
}
