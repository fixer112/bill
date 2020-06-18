<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Electricity extends Component
{
    public $discount;
    public $guest;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($discount, $guest = false)
    {
        $this->discount = $discount;
        $this->guest = $guest;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.electricity');
    }
}