<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public $id;
    public $name;
    public $rows;

    public function __construct($id, $name, $rows = 3)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.forms.textarea');
    }
}
