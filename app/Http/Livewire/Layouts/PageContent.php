<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;

class PageContent extends Component
{
    public $content;

    public function render()
    {
        return view('livewire.layouts.page-content');
    }
}
