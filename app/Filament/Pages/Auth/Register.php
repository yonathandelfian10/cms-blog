<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    // Fungsi ini dijalankan saat tombol "Sign Up" ditekan
    protected function handleRegistration(array $data): Model
    {
        // 1. Jalankan proses registrasi standar (simpan nama, email, password)
        $user = parent::handleRegistration($data);

        // 2. LOGIKA TAMBAHAN: Berikan Role 'user' otomatis
        // Pastikan role 'user' sudah dibuat di menu Shield/Roles sebelumnya
        $user->assignRole('user');

        // 3. Kembalikan data user yang sudah jadi
        return $user;
    }
}