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
        'category_id',
        'title',
        'slug',
        'content',
        'cover_file_url',
        'status',
        'view_count',
        'published_at'
    ];


    protected $casts = [
        'published_at' => 'datetime', // Agar dibaca sebagai format tanggal
    ];

    // --- RELASI KE USER ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    { // <--- Tambahkan ini
        return $this->belongsTo(Category::class);
    }
}