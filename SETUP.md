# Task Planner - Setup Instructions

## Langkah-langkah Setup

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_planner
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install Laravel Sanctum (untuk API Authentication)
```bash
php artisan install:api
```

### 5. Jalankan Migration
```bash
php artisan migrate
```

### 6. Jalankan Server
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

### 7. Jalankan Task Scheduler (untuk Cleanup Token)

Untuk production, tambahkan cron job:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Untuk development (testing scheduler):
```bash
php artisan schedule:work
```

Atau jalankan manual untuk cleanup token expired:
```bash
php artisan sanctum:prune-expired --hours=168
```

---

## Testing API

Gunakan Postman atau tool API testing lainnya untuk menguji endpoint.

### Contoh Flow:
1. Register merchant baru
2. Login untuk mendapatkan token
3. Gunakan token di header `Authorization: Bearer {token}` untuk mengakses endpoint lainnya
4. Buat task
5. Buat task items untuk task tersebut

---

## File Struktur

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── MerchantAuthController.php
│           ├── TaskController.php
│           └── TaskItemController.php
├── Models/
│   ├── Merchant.php
│   ├── Task.php
│   └── TaskItem.php
database/
├── migrations/
│   ├── 2025_10_24_001431_create_merchants_table.php
│   ├── 2025_10_24_001432_create_tasks_table.php
│   └── 2025_10_24_001433_create_task_items_table.php
routes/
├── api.php (API Routes)
└── web.php
```

---

## Database Schema

### Merchants Table
- id
- name
- email (unique)
- password
- phone
- address
- remember_token
- timestamps

### Tasks Table
- id
- merchant_id (foreign key)
- title
- description
- due_at
- status (todo, in progress, done)
- sort_order
- timestamps

### Task Items Table
- id
- task_id (foreign key)
- merchant_id (foreign key)
- title
- description
- status (todo, in progress, done)
- sort_order
- timestamps

---

## Fitur

### Merchant Authentication
✅ Register
✅ Login
✅ Logout
✅ Get Profile
✅ Forgot Password
✅ Reset Password

### Tasks Management
✅ Create Task
✅ Read Tasks (List & Detail)
✅ Update Task
✅ Delete Task
✅ Update Task Status
✅ Update Task Sort Order

### Task Items Management
✅ Create Task Item
✅ Read Task Items (List & Detail)
✅ Update Task Item
✅ Delete Task Item
✅ Update Task Item Status
✅ Update Task Item Sort Order

---

## Security
- Password di-hash menggunakan bcrypt
- API protected menggunakan Laravel Sanctum
- Setiap merchant hanya bisa mengakses data miliknya sendiri
- Validasi input di setiap endpoint
