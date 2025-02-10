<?php

namespace App\Livewire\Asset;

use Livewire\Component;

class CreateAsset extends Component
{

    public $count = 12;

    public function render()
    {
        return view('livewire.asset.create-asset');
    }

    public function increase()
    {
        $this->count = $this->count + 1;
    }
}
