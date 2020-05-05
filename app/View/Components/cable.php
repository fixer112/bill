<?php

namespace App\View\Components;

use Illuminate\View\Component;

class cable extends Component
{
    public $dat;
    public $guest;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dat,bool $guest)
    {
        $this->dat = $dat;
        $this->guest = $guest;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.cable');
    }
}