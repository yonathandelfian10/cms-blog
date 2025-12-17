<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Kolom apa saja yang boleh diisi datanya
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
    ];

    // --- RELASI KE USER ---
    public function user()
    {
        // Satu Post dimiliki oleh satu User
        return $this->belongsTo(User::class);
    }
}