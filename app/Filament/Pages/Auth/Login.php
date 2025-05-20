<?php

namespace App\Filament\Pages\Auth;

use Database\Factories\AdminFactory;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;

final class Login extends \Filament\Pages\Auth\Login
{
    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $data = !App::isProduction()
            ? [
                'email' => AdminFactory::ADMIN_EMAIL,
                'password' => AdminFactory::ADMIN_PASSWORD,
            ]
            : [];

        $this->form->fill($data);
    }
}
