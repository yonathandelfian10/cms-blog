<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman Utama (Landing Page)
    public function index()
    {
        // Ambil artikel yang statusnya 'published', urutkan terbaru
        $posts = Post::where('status', 'published')
            ->with(['category', 'user']) // Eager loading (Penting buat Laporan KP!)
            ->latest()
            ->get();

        // Ambil event terbaru
        $events = Event::latest()->get();

        return view('home', compact('posts', 'events'));
    }

    // Halaman Baca Artikel (Detail)
    public function show(Post $post)
    {
        // --- LOGIC PENTING (ANALISA PERFORMA) ---
        // Setiap halaman ini dibuka, tambah 1 ke view_count
        $post->increment('view_count');
        // ----------------------------------------

        return view('post-detail', compact('post'));
    }
}