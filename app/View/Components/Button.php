<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $disabled, $autofocus, $bgColor, $textColor;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($disabled = false, $autofocus = false, $bgColor = null, $textColor = null)
    {
        $this->disabled = $disabled;
        $this->autofocus = $autofocus;
        $this->bgColor = $bgColor;
        $this->textColor = $textColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}
