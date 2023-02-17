<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $disabled, $required, $readonly;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($disabled = false, $required = false, $readonly = false)
    {
        $this->disabled = $disabled;
        $this->required = $required;
        $this->readonly = $readonly;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
