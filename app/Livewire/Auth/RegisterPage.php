<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title("Register")]
class RegisterPage extends Component
{
    public $name;
    public $email; // Tambahkan properti email
    public $password;

    public function save()
    {
        // Validasi data input
        $validatedData = $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        // Simpan data ke database
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Login user
        auth()->login($user);

        // Flash success message
        session()->flash('message', 'Registration successful!');

        // Redirect ke halaman yang diinginkan
        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
