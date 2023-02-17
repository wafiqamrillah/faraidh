<?php

namespace App\Http\Traits\Livewire;

trait InteractsWithModal
{
    protected function openModal(string $form, $params = [], ?string $title = NULL,  array $options = ['header' => TRUE, 'footer' => FALSE], ?string $modalSize = NULL)
    {
        $this->emitTo('layouts.modal', 'showModal', $form, $params, $title, $options, $modalSize);
    }

    protected function closeModal()
    {
        $this->emitTo('layouts.modal', 'closeModal');
    }
    
    protected function switchModal(string $form, $params = [], ?string $title = NULL,  array $options = ['header' => TRUE, 'footer' => FALSE], ?string $modalSize = NULL){
        $this->dispatchBrowserEvent('switch-modal', [$form, $params, $title, $options, $modalSize]);
    }
}