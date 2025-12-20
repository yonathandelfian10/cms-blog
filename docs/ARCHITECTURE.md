# LevelUp – Best Practice Guide

## Fokus Dokumen
Dokumen ini bertujuan membantu **intern** memahami dan menerapkan **best practice** dalam pengembangan aplikasi dengan fokus pada:

**View Layer → Service Layer → Repository Layer**

dengan prinsip utama **Separation of Concern (SoC)**.

Dokumen ini **wajib diikuti** dalam seluruh pengembangan fitur LevelUp Blog.

---

## 1. Prinsip Utama Separation of Concern (SoC)

Separation of Concern berarti setiap bagian sistem memiliki **satu tanggung jawab utama** dan tidak saling mencampuri urusan layer lain.

### Prinsip Dasar
- Setiap layer hanya mengerjakan satu jenis tanggung jawab
- Business logic tidak boleh berada di View
- View tidak boleh tahu bagaimana data disimpan
- Repository tidak boleh tahu aturan bisnis
- Perubahan di satu layer tidak memaksa perubahan di layer lain

---

## 2. View Layer (Presentation Layer)

### Tanggung Jawab
View Layer bertanggung jawab atas **tampilan UI dan interaksi user**.

Contoh pada LevelUp Blog:
- Filament Resource
- Filament Page
- Filament Form

### Yang Boleh Dilakukan
- Menampilkan data
- Mengambil input user
- Memanggil Service
- Menampilkan notifikasi sukses/gagal

### Yang Tidak Boleh Dilakukan ❌
- Query database langsung
- Menggunakan Model untuk logika bisnis
- Validasi bisnis kompleks
- Mengatur relasi data

### Contoh Pola yang Benar
```
View → Service
```

---

## 3. Service Layer (Business Logic Layer)

### Tanggung Jawab
Service Layer berisi **aturan bisnis dan proses utama aplikasi**.

Layer ini adalah "otak" dari aplikasi.

### Yang Boleh Dilakukan
- Menentukan aturan bisnis
- Validasi bisnis (bukan validasi form)
- Mengatur alur proses (create, update, publish, dsb)
- Memanggil Repository

### Yang Tidak Boleh Dilakukan ❌
- Mengatur UI atau tampilan
- Mengakses database langsung
- Mengembalikan response HTML / UI

### Contoh Pola yang Benar
```
View → Service → Repository
```

---

## 4. Repository Layer (Data Access Layer)

### Tanggung Jawab
Repository Layer bertanggung jawab atas **akses data**.

Semua interaksi dengan database **HARUS melalui repository**.

### Yang Boleh Dilakukan
- Query database (Eloquent)
- Mengambil, menyimpan, mengubah, dan menghapus data
- Mengatur eager loading untuk menghindari N+1

### Yang Tidak Boleh Dilakukan ❌
- Menentukan aturan bisnis
- Mengatur flow aplikasi
- Mengakses UI atau Service lain

---

## 5. Alur Data yang Benar

Alur komunikasi antar layer **HARUS** seperti berikut:

```
View Layer
   ↓
Service Layer
   ↓
Repository Layer
   ↓
Database
```

❌ Contoh yang SALAH:
- View → Repository
- View → Model langsung
- Repository → Service

---

## 6. N+1 Query dan Repository

### Apa itu N+1 Query
N+1 terjadi ketika aplikasi:
- Mengambil 1 data utama
- Lalu melakukan query tambahan untuk setiap data terkait

Ini menyebabkan performa aplikasi buruk.

### Tanggung Jawab Repository
- Menggunakan eager loading (`with()`)
- Menyediakan method query yang optimal
- Tidak membiarkan View melakukan loop + query

### Prinsip Penting
> Jika N+1 terjadi, hampir selalu itu kesalahan desain Repository.

---

## 7. Kesalahan Umum Intern ❌

- Query Eloquent di Filament Resource
- Logic bisnis di form submit
- View langsung memanggil Model
- Repository berisi if/else aturan bisnis
- Sulit menjelaskan alur data sendiri

---

## 8. Target Pembelajaran Intern

Setelah mengikuti dokumen ini, intern diharapkan mampu:
- Menjelaskan perbedaan View, Service, dan Repository
- Menulis kode dengan SoC yang jelas
- Menghindari N+1 Query
- Membuat fitur yang mudah dirawat
- Menjelaskan *kenapa* arsitektur ini digunakan

---

## 9. Contoh Implementasi Kode (Laravel Filament)

Bagian ini memberikan contoh **implementasi nyata** bagaimana menerapkan SoC pada Laravel + Filament.

---

### 9.1 View Layer (Filament Resource)

**Tugas View:**
- Mengambil input user
- Memanggil Service
- Tidak mengandung business logic

```php
// app/Filament/Resources/PostResource/Pages/CreatePost.php
class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(PostService::class)->create($data);
    }
}
```

❌ **Yang salah (harus dihindari):**
```php
Post::create($data); // Query langsung di View
```

---

### 9.2 Service Layer (Business Logic)

**Tugas Service:**
- Menangani aturan bisnis
- Menentukan alur proses
- Memanggil Repository

```php
// app/Services/PostService.php
class PostService
{
    public function __construct(
        private PostRepository $repository
    ) {}

    public function create(array $data): Post
    {
        if (empty($data['title'])) {
            throw new DomainException('Judul wajib diisi');
        }

        $data['status'] = 'draft';

        return $this->repository->create($data);
    }
}
```

❌ **Yang salah:**
```php
// Mengatur UI / redirect di Service
return redirect()->back();
```

---

### 9.3 Repository Layer (Data Access)

**Tugas Repository:**
- Query database
- Optimasi query
- Eager loading

```php
// app/Repositories/PostRepository.php
interface PostRepository
{
    public function create(array $data): Post;
    public function findWithAuthor(int $id): Post;
}

// app/Repositories/EloquentPostRepository.php
class EloquentPostRepository implements PostRepository
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function findWithAuthor(int $id): Post
    {
        return Post::with('author')->findOrFail($id);
    }
}
```

---

### 9.4 Contoh N+1 dan Solusinya

❌ **Contoh Salah (N+1 Query):**
```php
$posts = Post::all();

foreach ($posts as $post) {
    echo $post->author->name; // Query berulang
}
```

✅ **Solusi di Repository:**
```php
public function allWithAuthor(): Collection
{
    return Post::with('author')->get();
}
```

View **tidak perlu tahu** soal eager loading.

---

## 10. Catatan Penting

Dokumen ini **lebih penting dari sekadar fitur jalan**.

Jika fitur berjalan tetapi melanggar SoC, maka fitur dianggap **BELUM SELESAI**.

> "Code that works but violates architecture is technical debt."

