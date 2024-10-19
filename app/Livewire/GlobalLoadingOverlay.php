<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GlobalLoadingOverlay extends Component
{
    public $isLoading = false;

    protected $listeners = [
        'startLoading' => 'startLoading',
        'stopLoading' => 'stopLoading',
    ];

    public function startLoading()
    {
        $this->isLoading = true;
    }

    public function stopLoading()
    {
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.global-loading-overlay');
    }
}
