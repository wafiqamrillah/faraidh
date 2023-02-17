<?php

namespace App\Http\Livewire\Modal;

use Livewire\Component;

// Traits
use App\Http\Traits\Livewire\InteractsWithModal;

class ShowPenjelasan extends Component
{
    use InteractsWithModal;

    public bool $show = false;

    public array $data = [
        'header' => [
            'asalmasalah' => 0,
        ],
        'list'  => [],
    ];

    public function load()
    {
        $this->show = true;
    }

    public function showJadwalFaraidh()
    {
        $data = $this->data;
        $this->switchModal('modal.show-jadwal-faraidh', compact('data'), 'Jadwal Faraidh', ['header' => TRUE, 'footer' => false], 'sm:max-w-5xl');
    }

    public function render()
    {
        return view('livewire.modal.show-penjelasan');
    }
}
