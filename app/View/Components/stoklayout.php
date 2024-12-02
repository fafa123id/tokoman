<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class stoklayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $barang;
    public function __construct($barang)
   {
       $this->barang = $barang;
   }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.stok-layout');
    }
}
