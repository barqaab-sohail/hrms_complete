<?php

namespace App\Livewire\Photocopy;

use Livewire\Component;

class Index extends Component
{
    
    public $testing ="It is verable from Component";
    
    public function render()
    {
        return view('livewire.photocopy.index');
    }
}
