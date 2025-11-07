<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConfirmationModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $custom;
    public function __construct($custom = false)
    {
        $this->custom = $custom;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $custom = $this->custom;
        return view('components.confirmation-modal',compact('custom'));
    }
}
