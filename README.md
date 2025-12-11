# Task Planner - Multi Merchant

Task Planner adalah aplikasi REST API untuk manajemen tugas (tasks) dengan fitur multi-merchant yang dibangun menggunakan Laravel 12.

## Fitur Utama

### 🔐 Merchant Authentication
- Register merchant baru
- Login/Logout
- Profile management
- Forgot password & Reset password

### 📋 Task Management
- CRUD operations untuk tasks
- Update status task (todo, in progress, done)
- Update sort order
- Filter tasks by merchant

### ✅ Task Items Management
- CRUD operations untuk task items (subtasks)
- Update status task items
- Update sort order
- Relasi dengan parent task

## Tech Stack

- **Framework**: Laravel 12
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/PostgreSQL
- **API**: RESTful API

## Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_planner
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install API & Run Migrations
```bash
php artisan install:api
php artisan migrate
```

### 5. Start Server
```bash
php artisan serve
```

## Documentation

- 📖 [Setup Instructions](SETUP.md)
- 📚 [API Documentation](API_DOCUMENTATION.md)

## Database Schema

### Tables
- **merchants** - Menyimpan data merchant/user
- **tasks** - Menyimpan data tasks utama
- **task_items** - Menyimpan sub-tasks dari tasks

### Relationships
- Merchant `hasMany` Tasks
- Merchant `hasMany` TaskItems
- Task `hasMany` TaskItems
- Task `belongsTo` Merchant
- TaskItem `belongsTo` Task
- TaskItem `belongsTo` Merchant

## API Endpoints

### Authentication
- `POST /api/merchant/register` - Register merchant baru
- `POST /api/merchant/login` - Login merchant
- `POST /api/merchant/logout` - Logout merchant
- `GET /api/merchant/profile` - Get merchant profile
- `POST /api/merchant/forgot-password` - Request password reset
- `POST /api/merchant/reset-password` - Reset password

### Tasks
- `GET /api/tasks` - Get all tasks
- `POST /api/tasks` - Create new task
- `GET /api/tasks/{id}` - Get task detail
- `PUT /api/tasks/{id}` - Update task
- `DELETE /api/tasks/{id}` - Delete task
- `PATCH /api/tasks/{id}/status` - Update task status
- `PATCH /api/tasks/{id}/sort-order` - Update task sort order

### Task Items
- `GET /api/tasks/{taskId}/items` - Get all task items
- `POST /api/tasks/{taskId}/items` - Create new task item
- `GET /api/tasks/{taskId}/items/{id}` - Get task item detail
- `PUT /api/tasks/{taskId}/items/{id}` - Update task item
- `DELETE /api/tasks/{taskId}/items/{id}` - Delete task item
- `PATCH /api/tasks/{taskId}/items/{id}/status` - Update task item status
- `PATCH /api/tasks/{taskId}/items/{id}/sort-order` - Update task item sort order

## Testing with Postman

1. Register merchant baru melalui `/api/merchant/register`
2. Login untuk mendapatkan access token
3. Gunakan token di header: `Authorization: Bearer {your_token}`
4. Akses endpoint yang membutuhkan authentication

## Security Features

- ✅ Password hashing dengan bcrypt
- ✅ API authentication dengan Laravel Sanctum
- ✅ Data isolation per merchant
- ✅ Input validation di semua endpoints
- ✅ CSRF protection

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
