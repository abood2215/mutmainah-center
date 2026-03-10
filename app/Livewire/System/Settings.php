<?php

namespace App\Livewire\System;

use Livewire\Component;

class Settings extends Component
{
    public function render()
    {
        return view('livewire.system.settings')->layout('layouts.app');
    }
}
