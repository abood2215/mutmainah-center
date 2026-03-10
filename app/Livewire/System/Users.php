<?php

namespace App\Livewire\System;

use Livewire\Component;

class Users extends Component
{
    public function render()
    {
        return view('livewire.system.users')->layout('layouts.app');
    }
}
