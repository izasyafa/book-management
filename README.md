# Book Management - Laravel 11


## 🛠 Persyaratan Sistem

Sebelum menginstal, pastikan sistem lo memenuhi persyaratan berikut:

- PHP **>= 8.2**
- Composer
- MySQL
- Laragon / XAMPP

---

## 🚀 Cara Instalasi

### 1️⃣ Clone Repository
```bash
 git clone  https://github.com/izasyafa/book-management.git
 cd book-management
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Buat File `.env`
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

### 4️⃣ Konfigurasi Database
Edit file `.env` untuk menyesuaikan koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_management
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ Generate Application Key
```bash
php artisan key:generate
```

### 6️⃣ Jalankan Migrasi Database
```bash
php artisan migrate --seed
```

### 7️⃣ Jalankan Server
```bash
php artisan serve
```

### 🔹 Menjalankan Storage Link
```bash
php artisan storage:link
```

### 🔹 Menjalankan Seeder
```bash
php artisan db:seed
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=UserSeeder
```

---

## 🎯 Akun Default (Jika Menggunakan Seeder)
```plaintext
Email: admin@example.com
Password: admin123
Role : Admin

Email : user@example.com
Password: writer123
Role: Writer
```
🔥 **Happy Coding!** 🔥
