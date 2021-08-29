<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Concerns\Faraidh;

class MainLivewire extends Component
{
    use Faraidh;

    public array $lists = [];

    public function mount()
    {
        $this->lists = $this->getAhliWaris();
    }

    public function render()
    {
        return view('livewire.main-livewire');
    }
}
