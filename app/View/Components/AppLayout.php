<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * The slot content.
     */
    public $slot;

    /**
     * Create a new component instance.
     */
    public function __construct($slot = null)
    {
        $this->slot = $slot;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
