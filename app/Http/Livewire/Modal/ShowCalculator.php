<?php

namespace App\Http\Livewire\Modal;

use Livewire\Component;

class ShowCalculator extends Component
{
    public array $data = [];

    public int $harta = 0;

    public function render()
    {
        return view('livewire.modal.show-calculator');
    }
}
