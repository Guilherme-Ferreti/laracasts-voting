<?php

namespace App\Http\Livewire\Traits;

Trait WithAuthRedirects
{
    public function redirectToLogin()
    {
        redirect()->setIntendedUrl(url()->previous());
            
        return redirect()->route('login');
    }

    public function redirectToRegister()
    {
        redirect()->setIntendedUrl(url()->previous());
            
        return redirect()->route('register');
    }
}
