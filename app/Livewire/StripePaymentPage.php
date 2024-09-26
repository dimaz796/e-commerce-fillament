<?php

namespace App\Livewire;

use Livewire\Component;

class StripePaymentPage extends Component
{
    public function mount()
    {
        // Panggil method saveOrder di CheckoutPage untuk menyimpan order setelah pembayaran sukses
        $checkoutPage = new CheckoutPage;
        $checkoutPage->saveOrder('paid'); // Menyimpan dengan status 'paid'

        // Redirect ke halaman sukses
        return redirect()->route('checkout.success');
    }

    public function render()
    {
        return view('livewire.stripe-success-page');
    }
}
