<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;

class Modal extends Component
{
    protected $listeners = [
        'showModal'     => 'open',
        'closeModal'    => 'close',
        'refreshModal'  => '$refresh',
    ];

    public bool $readyToLoad        = FALSE;

    public string $title            = '';
    public string $modalSize        = 'sm:max-w-2xl';
    public array $params            = [];
    public string $livewireContent  = '';
    public array $options           = [
        'header'    => TRUE,
        'footer'    => FALSE,
    ];

    public function open(string $livewireContent, array $params = [], ?string $title = NULL, array $options = ['header' => TRUE, 'footer' => FALSE], ?string $modalSize = 'sm:max-w-2xl')
    {
        $this->readyToLoad      = FALSE;
        $this->livewireContent  = $livewireContent;
        $this->params           = $params;
        $this->title            = $title;
        $this->options          = $options;
        $this->modalSize        = $modalSize ?? 'sm:max-w-2xl';

        $this->dispatchBrowserEvent('show-modal');
    }

    public function switch($param)
    {
        $this->readyToLoad      = TRUE;
        $this->livewireContent  = $param[0];
        $this->params           = $param[1];
        $this->title            = $param[2];
        $this->options          = $param[3] ?? ['header' => TRUE, 'footer' => FALSE];
        $this->modalSize        = $param[4] ?? 'sm:max-w-2xl';
    }

    public function close()
    {
        $this->dispatchBrowserEvent('close-modal');
    }

    public function clear()
    {
        $this->reset('livewireContent', 'readyToLoad');
    }

    public function render()
    {
        return view('livewire.layouts.modal');
    }
}
