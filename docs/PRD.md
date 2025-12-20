# PRD – LevelUp Blog

## 1. Judul Proyek
**LevelUp – Blog**

## 2. Deskripsi
LevelUp Blog adalah sebuah **Content Management System (CMS)** untuk LevelUp Indonesia yang digunakan untuk mengelola konten blog, event, dan pengguna. Sistem ini akan dibangun menggunakan Laravel + Filament dengan fokus pada arsitektur yang rapi, terstruktur, dan mudah dikembangkan oleh tim, khususnya **anak magang**.

Proyek ini juga berfungsi sebagai media pembelajaran bagi intern untuk memahami praktik **software engineering yang baik**, terutama dalam hal arsitektur, pemisahan tanggung jawab, dan implementasi fitur berbasis kebutuhan nyata.

---

## 3. Tujuan (Goals)

### Tujuan Produk
- Menyediakan CMS internal untuk LevelUp Indonesia
- Mempermudah pengelolaan konten blog dan event
- Menjamin keamanan akses berdasarkan peran (role)

### Tujuan Pembelajaran Intern
- Intern dapat membuat **CRUD** untuk seluruh fitur
- Intern dapat mengimplementasikan **Google Login, RBAC, dan integrasi Google Drive**
- Intern memahami dan menerapkan **Layered Architecture / Domain Driven Design (DDD)**
- Intern memahami dan menerapkan **Separation of Concern (SoC)**
- Intern mampu mengidentifikasi, memahami, dan menyelesaikan masalah **N+1 Query**
- Intern memahami:
  - Apa itu masalah **N+1 Query**
  - Dampak N+1 terhadap performa aplikasi
  - Cara mendeteksi N+1 (debug query, log, atau Laravel Debugbar)
  - Cara menyelesaikan N+1 menggunakan **Eager Loading**, **Query Optimization**, dan desain relasi yang tepat
- Intern terbiasa membuat kode yang **performant, maintainable, dan scalable**

---

## 4. Ruang Lingkup (Scope)

### 4.1 Pengguna (User)

#### Sebagai User:
- User diharapkan bisa login menggunakan akun Google
- User diharapkan bisa melihat daftar post yang dipublikasikan
- User diharapkan bisa melihat detail post
- User diharapkan bisa melihat daftar event
- User diharapkan bisa melihat detail event

#### Sebagai Admin:
- Admin bisa membuat, melihat, mengubah, dan menghapus data pengguna (CRUD User)
- Admin bisa mengatur role dan permission pengguna
- Admin bisa menonaktifkan atau mengaktifkan akun pengguna

---

### 4.2 Post (Blog)

#### Sebagai Admin / Editor:
- Admin bisa membuat post baru
- Admin bisa mengedit post
- Admin bisa menghapus post
- Admin bisa melihat daftar seluruh post
- Admin bisa mengatur status post (draft / published)
- Admin bisa mengunggah gambar atau file pendukung ke Google Drive

#### Sebagai User:
- User hanya bisa melihat post yang berstatus published

---

### 4.3 Event

#### Sebagai Admin / Editor:
- Admin bisa membuat event baru
- Admin bisa mengedit event
- Admin bisa menghapus event
- Admin bisa melihat daftar event
- Admin bisa mengunggah poster atau file event ke Google Drive

#### Sebagai User:
- User bisa melihat daftar event
- User bisa melihat detail event

---

### 4.4 Autentikasi & Otorisasi

#### Login Google
- Sistem menggunakan **Laravel Socialite** untuk login via Google
- Jika user belum terdaftar, sistem otomatis membuat akun baru
- Role default ditentukan saat user pertama kali login

Referensi:
- https://laraveldaily.com/post/filament-sign-in-with-google-using-laravel-socialite

---

## 5. Entity Relationship Diagram (ERD)

Bagian ini menjelaskan **struktur data domain utama** pada LevelUp Blog.

### 5.1 Entitas

#### User
- id (PK)
- name
- email
- google_id
- avatar_url
- is_active
- created_at
- updated_at

#### Post
- id (PK)
- user_id (FK → User.id)
- title
- slug
- content
- status (draft / published)
- cover_file_url (Google Drive)
- published_at
- created_at
- updated_at

#### Event
- id (PK)
- user_id (FK → User.id)
- title
- description
- event_date
- poster_file_url (Google Drive)
- created_at
- updated_at

---

### 5.2 Relasi Antar Entitas

- User **hasMany** Post
- User **hasMany** Event

---

### 5.3 Diagram Relasi (Konseptual)

```
User
 ├── hasMany → Post
 └── hasMany → Event
```

---

### 5.4 Catatan RBAC (Filament Shield)

Role dan permission **tidak dimodelkan dalam ERD domain**, karena:
- Seluruh manajemen role & permission ditangani oleh **Filament Shield**
- Tabel RBAC dianggap sebagai **infrastruktur framework**, bukan domain bisnis
- Intern **tidak perlu** membuat atau memodifikasi ERD untuk RBAC

ERD ini menjadi **acuan wajib** dalam:
- Pembuatan migration
- Desain repository
- Implementasi eager loading (mencegah N+1)
- Review arsitektur oleh mentor

---

## 7. Separation of Concern (SoC)

Intern diharapkan memahami bahwa:
- Filament Resource hanya bertugas menampilkan UI
- Business logic tidak boleh ditulis di Resource atau Controller
- Akses database dilakukan melalui Repository
- Integrasi eksternal dipisahkan dari domain logic

---

## 8. Backlog Pengembangan (Untuk Intern)

### Tahap 1 – Fundamental
- Setup project Laravel + Filament
- Setup autentikasi default
- Setup struktur folder arsitektur

### Tahap 2 – User Management
- CRUD User
- Implementasi RBAC
- Integrasi Google Login

### Tahap 3 – Post Management
- CRUD Post
- Upload file ke Google Drive
- Publish & Draft

### Tahap 4 – Event Management
- CRUD Event
- Upload poster ke Google Drive

### Tahap 5 – Arsitektur & Refactoring
- Memindahkan logic ke Service / Use Case
- Implementasi Repository Pattern
- Review SoC

---

## 9. Definition of Done (DoD)

Sebuah fitur dianggap selesai jika:
- CRUD berjalan dengan baik
- Role & permission sesuai
- Tidak ada business logic di layer presentation
- Kode mudah dibaca dan dipahami
- Intern bisa menjelaskan alur data dan arsitektur fitur tersebut

---

## 10. Catatan untuk Intern

Proyek ini bukan hanya tentang "fitur jalan", tetapi tentang:
- Cara berpikir sebagai software engineer
- Menulis kode untuk jangka panjang
- Memahami alasan di balik arsitektur

> "Clean architecture is not about frameworks, it is about boundaries."

