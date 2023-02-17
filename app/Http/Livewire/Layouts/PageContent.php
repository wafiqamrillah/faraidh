<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;

class PageContent extends Component
{
    public $readyToLoad = FALSE;

    public $content;

    public function loadPage()
    {
        $this->readyToLoad = TRUE;
    }

    public function render()
    {
        return view('livewire.layouts.page-content');
    }
}
