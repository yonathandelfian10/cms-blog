<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - LevelUp Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <nav class="bg-white shadow p-4">
        <div class="container mx-auto">
            <a href="/" class="text-blue-600 font-bold">← Kembali ke Home</a>
        </div>
    </nav>

    <article class="container mx-auto mt-8 max-w-3xl bg-white p-8 rounded shadow">

        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
            {{ $post->category->name ?? 'Umum' }}
        </span>
        <h1 class="text-4xl font-bold mt-4 mb-4 text-gray-900">{{ $post->title }}</h1>

        <div class="flex items-center text-gray-500 text-sm mb-8 border-b pb-4">
            <span class="mr-4">Ditulis oleh: <strong>{{ $post->user->name }}</strong></span>
            <span class="mr-4">📅 {{ $post->created_at->format('d M Y') }}</span>
            <span>👁️ {{ $post->view_count }} Views</span>
        </div>

        @if($post->cover_file_url)
            <img src="{{ asset('storage/' . $post->cover_file_url) }}" class="w-full rounded mb-8">
        @endif

        <div class="prose max-w-none text-gray-800 leading-relaxed">
            {!! $post->content !!}
        </div>

    </article>

    <footer class="text-center py-8 text-gray-500 text-sm">
        &copy; 2025 LevelUp Blog
    </footer>

</body>

</html>