<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // <--- Import ini
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName; // <--- 1. Tambahkan ini

class User extends Authenticatable implements FilamentUser, HasName // <--- 2. Implementasikan di sini
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // <--- Tambahkan HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'google_id',
        'avatar_url',
        'is_active', // <--- Tambahkan ini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Agar password otomatis di-hash
    ];

    // --- RELASI KE POST ---
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function events()
    { // <--- Tambahkan ini
        return $this->hasMany(Event::class);
    }

    // Fungsi untuk membatasi siapa yang bisa login Admin (PENTING untuk fitur Active/Inactive)
    public function canAccessPanel(Panel $panel): bool
    {
        // User bisa akses jika: Punya email verified DAN statusnya Active
        return $this->is_active;
    }

    // --- 3. TAMBAHKAN FUNGSI INI DI PALING BAWAH ---
    public function getFilamentName(): string
    {
        // Ambil nama role pertama (misal: 'super_admin')
        $role = $this->getRoleNames()->first();

        // Ubah jadi huruf besar yang rapi (misal: "Super Admin")
        $roleFormatted = str($role)->headline();

        // Gabungkan Nama User + Role
        // Hasilnya nanti: "Yonathan Sebastian - Super Admin"
        return "{$this->name} ({$roleFormatted})";
    }
}