# Online Library Management System

Sistem manajemen perpustakaan online yang dibangun dengan Laravel 12. Aplikasi ini menyediakan fitur untuk mengelola koleksi buku dengan antarmuka web dan REST API.

## Fitur Utama

- **Manajemen Buku**: CRUD lengkap untuk koleksi buku
- **Role-based Access Control**: Sistem akses berdasarkan role (Guest dan Admin)
- **Google Books API Integration**: Import buku populer dari Google Books API
- **REST API**: Endpoint API lengkap dengan autentikasi Sanctum
- **Responsive Design**: Antarmuka yang responsif menggunakan TailwindCSS

## Teknologi yang Digunakan

- **Framework**: Laravel 12
- **Authentication**: Laravel Breeze + Sanctum
- **Styling**: TailwindCSS
- **Database**: MySQL
- **API Authentication**: Laravel Sanctum

## Instalasi dan Setup

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- Node.js dan NPM

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd web_tigor
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database**
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=library_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan migration dan seeder**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Jalankan server**
   ```bash
   php artisan serve
   ```

   Aplikasi akan berjalan di `http://localhost:8000`

### Default Admin Credentials

Setelah menjalankan seeder, Anda dapat login dengan:
- **Email**: `admin@example.com`
- **Password**: `password` (default dari factory)

## Role dan Akses

### Guest (Tidak Login)

Guest dapat mengakses:
- ✅ Melihat daftar buku di halaman home (`/`)
- ✅ Melihat detail buku (`/book/{id}`)
- ✅ Melihat halaman About (`/about`)

### Admin

Admin dapat mengakses semua fitur Guest, plus:
- ✅ Login ke dashboard admin (`/admin/dashboard`)
- ✅ Menambah, mengedit, dan menghapus buku (`/admin/books`)
- ✅ Mengedit informasi About (`/admin/about`)
- ✅ Import buku dari Google Books API
- ✅ Mengakses semua endpoint API dengan autentikasi

## Struktur Halaman

### Halaman Public (Guest)

- **`/`** - Home: Menampilkan daftar semua buku
- **`/book/{id}`** - Detail Buku: Menampilkan informasi lengkap buku
- **`/about`** - About: Menampilkan informasi perpustakaan

### Halaman Admin

- **`/admin/dashboard`** - Dashboard: Statistik dan quick actions
- **`/admin/books`** - Manajemen Buku: CRUD buku
- **`/admin/about`** - Edit About: Form edit informasi perpustakaan

## REST API Documentation

Base URL: `http://localhost:8000/api`

### Authentication

API menggunakan Laravel Sanctum untuk autentikasi. Untuk mengakses endpoint yang memerlukan autentikasi, Anda perlu:

1. Login sebagai user melalui web interface
2. Generate token menggunakan:
   ```php
   $user = User::find(1);
   $token = $user->createToken('api-token')->plainTextToken;
   ```
3. Gunakan token di header request:
   ```
   Authorization: Bearer {token}
   ```

### Public Endpoints (Tidak Perlu Autentikasi)

#### GET /api/books

Mendapatkan daftar semua buku.

**Request:**
```http
GET /api/books
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "The Great Gatsby",
      "author": "F. Scott Fitzgerald",
      "description": "A classic American novel...",
      "cover_url": "https://...",
      "created_at": "2025-11-08T03:50:14.000000Z",
      "updated_at": "2025-11-08T03:50:14.000000Z"
    }
  ]
}
```

#### GET /api/books/{id}

Mendapatkan detail buku berdasarkan ID.

**Request:**
```http
GET /api/books/1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "description": "A classic American novel...",
    "cover_url": "https://...",
    "created_at": "2025-11-08T03:50:14.000000Z",
    "updated_at": "2025-11-08T03:50:14.000000Z"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Book not found."
}
```

### Protected Endpoints (Admin Only - Perlu Autentikasi)

#### POST /api/books

Menambahkan buku baru. **Hanya admin yang dapat mengakses.**

**Request:**
```http
POST /api/books
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Book Title",
  "author": "Author Name",
  "description": "Book description here...",
  "cover_url": "https://example.com/cover.jpg"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Book created successfully.",
  "data": {
    "id": 6,
    "title": "Book Title",
    "author": "Author Name",
    "description": "Book description here...",
    "cover_url": "https://example.com/cover.jpg",
    "created_at": "2025-11-08T10:00:00.000000Z",
    "updated_at": "2025-11-08T10:00:00.000000Z"
  }
}
```

**Validation Error (422):**
```json
{
  "message": "The title field is required. (and 1 more error)",
  "errors": {
    "title": ["The title field is required."],
    "author": ["The author field is required."]
  }
}
```

#### PUT /api/books/{id}

Mengupdate buku yang sudah ada. **Hanya admin yang dapat mengakses.**

**Request:**
```http
PUT /api/books/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Book Title",
  "author": "Updated Author Name",
  "description": "Updated description...",
  "cover_url": "https://example.com/new-cover.jpg"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Book updated successfully.",
  "data": {
    "id": 1,
    "title": "Updated Book Title",
    "author": "Updated Author Name",
    "description": "Updated description...",
    "cover_url": "https://example.com/new-cover.jpg",
    "created_at": "2025-11-08T03:50:14.000000Z",
    "updated_at": "2025-11-08T10:00:00.000000Z"
  }
}
```

#### DELETE /api/books/{id}

Menghapus buku. **Hanya admin yang dapat mengakses.**

**Request:**
```http
DELETE /api/books/1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Book deleted successfully."
}
```

### Error Responses

#### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

#### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized. Admin access required."
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Book not found."
}
```

#### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

## Contoh Penggunaan API

### Menggunakan cURL

**Get semua buku:**
```bash
curl -X GET http://localhost:8000/api/books
```

**Get detail buku:**
```bash
curl -X GET http://localhost:8000/api/books/1
```

**Tambah buku (perlu token):**
```bash
curl -X POST http://localhost:8000/api/books \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Book",
    "author": "Author Name",
    "description": "Book description",
    "cover_url": "https://example.com/cover.jpg"
  }'
```

**Update buku (perlu token):**
```bash
curl -X PUT http://localhost:8000/api/books/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "author": "Updated Author",
    "description": "Updated description",
    "cover_url": "https://example.com/new-cover.jpg"
  }'
```

**Hapus buku (perlu token):**
```bash
curl -X DELETE http://localhost:8000/api/books/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Menggunakan JavaScript (Fetch API)

```javascript
// Get semua buku
fetch('http://localhost:8000/api/books')
  .then(response => response.json())
  .then(data => console.log(data));

// Tambah buku
fetch('http://localhost:8000/api/books', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer YOUR_TOKEN_HERE',
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    title: 'New Book',
    author: 'Author Name',
    description: 'Book description',
    cover_url: 'https://example.com/cover.jpg'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Fitur Tambahan

### Google Books API Integration

Admin dapat mengimpor buku populer dari Google Books API melalui dashboard:
1. Login sebagai admin
2. Akses `/admin/dashboard`
3. Klik tombol "Import from Google Books"
4. Sistem akan mengimpor 5-10 buku populer secara otomatis

## Database Structure

### Tabel `users`
- `id` - Primary key
- `name` - Nama user
- `email` - Email user
- `password` - Password (hashed)
- `role` - Role user (guest/admin)
- `timestamps`

### Tabel `books`
- `id` - Primary key
- `title` - Judul buku
- `author` - Penulis buku
- `description` - Deskripsi buku
- `cover_url` - URL cover buku
- `timestamps`

### Tabel `about`
- `id` - Primary key
- `name` - Nama perpustakaan/admin
- `bio` - Bio/deskripsi
- `profile_image` - URL gambar profil
- `contact_info` - Informasi kontak
- `timestamps`

## Development

### Menjalankan Development Server

```bash
php artisan serve
```

### Menjalankan dengan Hot Reload (Vite)

```bash
npm run dev
```

Di terminal lain:
```bash
php artisan serve
```

### Testing

```bash
php artisan test
```

## License

MIT License

## Kontribusi

Silakan buat issue atau pull request jika ingin berkontribusi pada proyek ini.
