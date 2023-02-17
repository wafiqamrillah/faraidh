<?php

namespace App\Http\Livewire\Modal;

use Livewire\Component;
use App\Models\Concerns\Faraidh;

class ShowJadwalFaraidh extends Component
{
    use Faraidh;

    public array $data;
    public array $jadwal_for_0 = [''];

    public function getJadwalProperty()
    {
        return json_decode(json_encode($this->getJadwalFaraidh()), true);
    }

    public function load()
    {
        
    }

    public function render()
    {
        return view('livewire.modal.show-jadwal-faraidh');
    }
}
