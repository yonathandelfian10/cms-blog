<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LevelUp Blog - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <nav class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-blue-600">LevelUp Blog</a>

            <div class="flex gap-2">
                <a href="/admin/login" class="text-gray-600 hover:text-blue-600 font-medium px-3 py-2">
                    Login
                </a>

                <a href="/admin/register"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                    Daftar Akun
                </a>
            </div>
        </div>
    </nav>

    <header class="bg-blue-600 text-white py-20 text-center">
        <h1 class="text-4xl font-bold mb-2">Selamat Datang di LevelUp Blog</h1>
        <p class="text-lg">Berbagi Wawasan Seputar Teknologi & Karir</p>
    </header>

    <div class="container mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-2">
            <h2 class="text-2xl font-bold mb-4 border-b pb-2">Artikel Terbaru</h2>

            @foreach($posts as $post)
                <div class="bg-white p-6 rounded shadow mb-6 flex gap-4">
                    @if($post->cover_file_url)
                        <div class="w-1/3">
                            <img src="{{ asset('storage/' . $post->cover_file_url) }}" class="rounded object-cover h-32 w-full">
                        </div>
                    @endif

                    <div class="w-2/3">
                        <span class="text-sm text-blue-500 font-bold">{{ $post->category->name ?? 'Umum' }}</span>
                        <h3 class="text-xl font-bold mt-1">
                            <a href="{{ route('post.show', $post) }}" class="hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ strip_tags($post->content) }}</p>

                        <div class="mt-3 flex items-center text-xs text-gray-500 gap-4">
                            <span>👤 {{ $post->user->name }}</span>
                            <span>👁️ Dibaca {{ $post->view_count }} kali</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <h2 class="text-2xl font-bold mb-4 border-b pb-2">Event Mendatang</h2>
            @foreach($events as $event)
                <div class="bg-white p-4 rounded shadow mb-4">
                    <img src="{{ asset('storage/' . $event->poster_file_url) }}" class="rounded mb-2 w-full">
                    <h3 class="font-bold">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-600">📅 {{ $event->event_date->format('d M Y') }}</p>
                </div>
            @endforeach
        </div>

    </div>

</body>

</html>