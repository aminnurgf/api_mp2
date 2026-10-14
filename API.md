# Dokumentasi API

Base URL: `http://localhost:8000/api`

## Menjalankan

```powershell
php artisan migrate --seed      # buat tabel + admin default (admin / admin123)
php -S localhost:8000 -t public # jalankan server
```

## Format Response

```json
{ "success": true,  "message": "...", "data": { } }
{ "success": false, "message": "...", "errors": { } }   // errors hanya saat validasi (422)
```

## Endpoint

| Method | URL                 | Body (JSON)                         | Keterangan                    |
|--------|---------------------|-------------------------------------|-------------------------------|
| POST   | `/login`            | `username`, `password`              | 200 sukses / 401 salah        |
| GET    | `/admin`            | -                                   | List, opsional `?search=`     |
| GET    | `/admin/{id}`       | -                                   | Detail / 404                  |
| POST   | `/admin`            | `username` (min 3, unik), `password` (min 6) | 201 dibuat           |
| PUT    | `/admin/{id}`       | `username` dan/atau `password`      | Update sebagian               |
| DELETE | `/admin/{id}`       | -                                   | Hapus                         |

Password otomatis di-hash (bcrypt) dan tidak pernah dikembalikan di response.

## Cara Testing

1. **Otomatis (PHPUnit)** – memakai SQLite in-memory, data MySQL aman:
   ```powershell
   vendor\bin\phpunit
   ```
2. **Postman** – Import `postman_collection.json`.
3. **VS Code REST Client** – buka `api.http`, klik *Send Request*.
4. **curl**:
   ```powershell
   curl.exe -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d '{\"username\":\"admin\",\"password\":\"admin123\"}'
   curl.exe http://localhost:8000/api/admin
   ```

## Menambah Resource Baru (contoh: `produk`)

1. Migration: buat file di `database/migrations/` (salin dari migration admin), lalu `php artisan migrate`.
2. Model: `app/Models/Produk.php` (salin `Admin.php`, ubah `$table` & `$fillable`).
3. Controller: `app/Http/Controllers/ProdukController.php` (salin `AdminController.php`), gunakan `$this->success()` / `$this->error()`.
4. Route: salin blok `admin` di `routes/web.php`, ganti prefix menjadi `produk`.
5. Test: salin `tests/AdminApiTest.php`.

## Struktur

```
app/Http/Controllers/Controller.php       helper response success()/error()
app/Http/Controllers/AdminController.php  CRUD admin
app/Http/Controllers/AuthController.php   login
app/Models/Admin.php                      model (hash password otomatis)
app/Exceptions/Handler.php                error /api/* selalu JSON
database/migrations/..._create_admin_table.php
database/seeders/AdminSeeder.php
routes/web.php                            semua route API
tests/AdminApiTest.php                    test otomatis
```

