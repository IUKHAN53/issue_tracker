<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Config;
use Livewire\Component;

class UserSetting extends Component
{
    public $user;
    public $name;
    public $email;
    public $access_token;
    protected $rules = [
        'name' => '',
        'email' => '',
        'access_token' => '',
    ];

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->access_token = $this->user->personal_access_token;
    }

    public function render()
    {
        return view('livewire.user-setting');
    }

    public function save(){
        $this->validate();
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->personal_access_token = $this->access_token;
        $this->user->save();
        $this->mount();
    }
}
