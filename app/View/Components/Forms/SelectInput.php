<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectInput extends Component
{
    public $id;
    public $name;
    public $options;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $name, $options = [], $value = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.forms.select-input');
    }
}
