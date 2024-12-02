<?php

namespace App\View\Components;

use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\View\Component;
use Illuminate\View\View;

class MainAppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.mainApp');
    }
}
