<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Relasi ke User (Penulis)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // --- TAMBAHAN RELASI KE CATEGORY (DOSPEM) ---
            // Nullable dulu jaga-jaga kalau kategori dihapus, post tidak hilang
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            // --------------------------------------------

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');

            // --- TAMBAHAN SESUAI PRD & DOSPEM ---
            $table->string('cover_file_url')->nullable(); // Upload ke GDrive
            $table->enum('status', ['draft', 'published'])->default('draft'); // Status
            $table->bigInteger('view_count')->default(0); // Counter (Dospem)
            $table->timestamp('published_at')->nullable(); // Kapan terbit
            // ------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
