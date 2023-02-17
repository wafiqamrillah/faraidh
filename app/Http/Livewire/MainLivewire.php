<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Concerns\Faraidh;

// Traits
use App\Http\Traits\Livewire\InteractsWithModal;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MainLivewire extends Component
{
    use Faraidh;
    use LivewireAlert;
    use InteractsWithModal;

    public array $form = [];
    public array $result = [];

    public bool $showResult = false;

    public function mount()
    {
        $this->resetField();
    }

    public function getListsProperty()
    {
        return $this->getAhliWaris();
    }

    public function resetField()
    {
        $this->reset();

        foreach ($this->lists as $item) {
            $this->form[$item->value] = 0;
        }
    }

    public function start()
    {
        try {
            $data = array_filter($this->form, fn($value) => $value != 0);
            if (count($data) == 0) {
                $this->alert('warning', 'Anda belum memasukkan data! Periksa kembali!', [
                    'showConfirmButton' => true,
                ]);

                $this->resetField();
            } else {
                $data = json_decode(json_encode(array_map(function($value, $key){ return ['waris' => $key, 'jumlah' => $value]; }, $data, array_keys($data))));
                
                $this->result = json_decode(json_encode($this->process($data)), true);
        
                $this->showResult = true;

                $this->alert('success', 'Berhasil! Proses perhitungan selesai!', [
                    'toast' => true,
                    'position' => 'bottom',
                    'timerProgressBar' => true,
                    'width' => '100%',
                ]);
            }
        } catch (\Exception $e) {
            $this->alert('warning', $e->getMessage());
            // $this->resetField();
        }
    }

    public function showDalil($number)
    {
        $dalil = $this->getDalil($number);

        $this->openModal('modal.show-dalil', compact('dalil'), __('Dalil'), ['header' => TRUE, 'footer' => TRUE]);
    }

    public function showPenjelasan()
    {
        $result = $this->result;
        $data = [
            'header'    => [
                'asalmasalah'   => $result[0]['asalmasalah'] ?? 0
            ],
            'list'      => $result,
        ];

        $this->openModal('modal.show-penjelasan', compact('data'), __('Penjelasan'), ['header' => TRUE, 'footer' => FALSE], 'sm:max-w-7xl');
    }

    public function showCalculator()
    {
        $data = $this->result;
        $this->openModal('modal.show-calculator', compact('data'), __('Kalkulator Waris'), ['header' => TRUE, 'footer' => TRUE]);
    }

    public function showJadwalFaraidh()
    {
        $data = $this->result;
        $this->openModal('modal.show-jadwal-faraidh', compact('data'), 'Jadwal Faraidh');
    }

    public function render()
    {
        return view('livewire.main-livewire');
    }
}
