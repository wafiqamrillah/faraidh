<?php

namespace App\Http\Livewire\Modal;

// Traits
use App\Http\Traits\Livewire\InteractsWithModal;
use Livewire\Component;

class ShowDalil extends Component
{
    use InteractsWithModal;

    public array $dalil = [
        'arab' => '',
        'penjelasan' => '',
        'sumber' => '',
    ];

    public function render()
    {
        return view('livewire.modal.show-dalil');
    }
}
