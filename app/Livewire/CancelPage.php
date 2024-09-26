<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Payment Cancelled')]
class CancelPage extends Component
{
    public $errorMessage = '';

    public function mount()
    {
        $this->errorMessage = session('error') ?? 'Your payment was cancelled or failed to process.';
    }

    public function tryAgain()
    {
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.cancel-page');
    }
}