<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ActiveIssues extends Component
{
    public $issues;
    public $tasks;
    public $invalid_token;

    public function mount()
    {
        try {
            $this->issues = getClient()->issues()->all();
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                $this->invalid_token = true;
            } else {
                dd($e);
            }
        }
        if (!$this->invalid_token) {
            $tasks_array = getTasks($this->issues)->toArray();
            usort($tasks_array, fn($a, $b) => $b['updated'] <=> $a['updated']);
            $this->tasks = collect($tasks_array)->take(10);
        }
    }

    public function render()
    {
        return view('livewire.active-issues');
    }

}
