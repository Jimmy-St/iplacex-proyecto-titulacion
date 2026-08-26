<?php

namespace App\Livewire;

use Livewire\Component;

class DemoShow extends Component
{
    public $numero;

    public function render()
    {
        return view('livewire.demo-show')
            ->layout('layouts.app', ['slot' => 'content']); // Fuerza a Livewire a usar la sección content
    }
}
