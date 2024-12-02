<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class agentCard extends Component
{
    public $agent;
    /**
     * Create a new component instance.
     */
    public function __construct($agent)
    {
        $this->agent=$agent;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.agent-card');
    }
}
