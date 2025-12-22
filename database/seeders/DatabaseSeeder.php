<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Cache Permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ---------------------------------------------------------
        // TAHAP 1: BUAT PERMISSION SECARA MANUAL (PASTI & CEPAT)
        // ---------------------------------------------------------

        // Daftar Permission yang kita butuhkan untuk CMS
        $permissions = [
            // Permission untuk User
            'view_any_user',
            'create_user',
            'update_user',
            'delete_user',

            // Permission untuk Category
            'view_any_category',
            'create_category',
            'update_category',
            'delete_category',

            // Permission untuk Post
            'view_any_post',
            'create_post',
            'update_post',
            'delete_post',

            // Permission untuk Event
            'view_any_event',
            'create_event',
            'update_event',
            'delete_event',

            // Permission untuk Role (Shield)
            'view_any_role',
            'create_role',
            'update_role',
            'delete_role',
        ];

        // Loop untuk membuat permission di database
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $this->command->info('✅ Permissions berhasil dibuat manual.');

        // ---------------------------------------------------------
        // TAHAP 2: SETUP ROLE & ASSIGN PERMISSION
        // ---------------------------------------------------------

        // A. Role ADMIN (Ganti Super Admin jadi Admin)
        // Admin dapat SEMUA permission yang kita buat di atas
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions(Permission::all());

        // B. Role EDITOR
        // Editor hanya boleh kelola Konten (Post, Event, Category)
        $roleEditor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $roleEditor->syncPermissions([
            'view_any_post',
            'create_post',
            'update_post',
            'delete_post',
            'view_any_event',
            'create_event',
            'update_event',
            'delete_event',
            'view_any_category',
            'create_category',
            'update_category',
            'delete_category',
        ]);

        // C. Role USER
        // User tidak punya permission ke panel admin
        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $roleUser->syncPermissions([]); // Kosong

        // ---------------------------------------------------------
        // TAHAP 3: BUAT USER DENGAN ROLE BARU
        // ---------------------------------------------------------

        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Yonathan Admin',
                'password' => Hash::make('password'),
                'phone_number' => '081234567890',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $admin->assignRole($roleAdmin);

        // 2. Akun Editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@levelup.com'],
            [
                'name' => 'Budi Content Writer',
                'password' => Hash::make('password'),
                'phone_number' => '08987654321',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $editor->assignRole($roleEditor);

        // 3. Akun User
        $user = User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Pembaca',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $user->assignRole($roleUser);

        // ---------------------------------------------------------
        // TAHAP 4: ISI DATA DUMMY (KATEGORI, POST, EVENT)
        // ---------------------------------------------------------

        // Buat Kategori
        $catTekno = Category::firstOrCreate(['slug' => 'teknologi-ai'], ['name' => 'Teknologi & AI']);
        $catKarir = Category::firstOrCreate(['slug' => 'tips-karir'], ['name' => 'Tips Karir']);
        $catCoding = Category::firstOrCreate(['slug' => 'tutorial-coding'], ['name' => 'Tutorial Coding']);
        $catInfo = Category::firstOrCreate(['slug' => 'info-levelup'], ['name' => 'Info LevelUp']);

        // Buat Postingan (Cek dulu biar gak duplikat kalau seed dijalankan 2x)
        if (Post::count() == 0) {
            Post::create([
                'user_id' => $admin->id,
                'category_id' => $catKarir->id,
                'title' => 'Cara Membuat CV ATS Friendly Agar Dilirik HRD',
                'slug' => 'cara-membuat-cv-ats-friendly',
                'content' => '<p>Panduan lengkap membuat CV ATS...</p>',
                'status' => 'published',
                'view_count' => 1250,
                'published_at' => now()->subDays(10),
            ]);

            Post::create([
                'user_id' => $editor->id,
                'category_id' => $catCoding->id,
                'title' => 'Tutorial Laravel 10 untuk Pemula',
                'slug' => 'tutorial-laravel-10-pemula',
                'content' => '<p>Langkah instalasi Laravel...</p>',
                'status' => 'published',
                'view_count' => 845,
                'published_at' => now()->subDays(5),
            ]);
        }

        // Buat Event
        if (Event::count() == 0) {
            Event::create([
                'user_id' => $admin->id,
                'title' => 'Webinar: Strategi Lolos Interview',
                'description' => 'Webinar gratis...',
                'event_date' => now()->addDays(7),
            ]);
        }

        $this->command->info('✅ Setup Selesai!');
        $this->command->info('👤 Admin  : admin@admin.com | password');
        $this->command->info('👤 Editor : editor@levelup.com | password');
        $this->command->info('👤 User   : siti@gmail.com | password');
    }
}