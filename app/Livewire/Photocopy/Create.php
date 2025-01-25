<?php

namespace App\Livewire\Photocopy;

use Livewire\Component;
use App\Models\Photocopy\Photocopy;

class Create extends Component
{
    

    public $name = "";

    public $total = [];

    public function createNewRecord(){
        
       $this->dispatch('openModal');
       
    }
    
    
    public function render()
    {
        $this->total = Photocopy::all();
        return view('livewire.photocopy.create');
    }
}
