<?php

namespace App\View\Components;

use App\User;
use Illuminate\View\Component;

class Subscribe extends Component
{

    public $user;
    public $message;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.subscribe');
    }
}
