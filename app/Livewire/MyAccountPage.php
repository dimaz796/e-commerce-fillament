<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MyAccountPage extends Component
{
    use WithFileUploads;

    public $photo;
    public $currentPhoto;
    public $name;
    public $email;

    public function mount()
    {
        $user = auth()->user();
        $this->currentPhoto = $user->profile_image;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updatePhoto(){
        $this->currentPhoto = $this->photo->temporaryUrl();
    }


    public function updateAccount()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'photo' => 'nullable|image|max:1024', // Validasi foto opsional
        ]);
    
        // Jika ada foto baru, simpan dan update
        if ($this->photo) {
            $path = $this->photo->store('profile_image', 'public');
            auth()->user()->update([
                'profile_image' => $path,
            ]);
        }
    
        // Update nama dan email
        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
    
        session()->flash('success', 'Account info updated successfully!');
    }

    public function render()
    {
// dd($this->currentPhoto);
        return view('livewire.my-account-page', [
            'user' => Auth::user(),
            'currentPhoto' => $this->currentPhoto
        ]);
    }
}
