<?php

namespace App\View\Components;

use App\User;
use Illuminate\View\Component;

class Subscribe extends Component
{

    public $user;
    public $message;
    public $packages;
    public $upgrade;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(User $user, String $message, array $packages, bool $upgrade = false)
    {
        $this->user = $user;
        $this->message = $message;
        $this->packages = $packages;
        $this->upgrade = $upgrade;
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