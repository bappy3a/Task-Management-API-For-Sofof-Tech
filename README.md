# 📋 Task Management API – For Sofof Tech

A simple and scalable task management RESTful API built with Laravel. This backend application helps manage tasks, users, and roles, and is ready for integration with frontend web apps or mobile applications.

---

## 🔧 Tech Stack

- PHP 8.3
- Laravel 12
- MySQL
- Composer

---

## 🚀 Features

- User Authentication (register, login) *(if implemented)*
- Task Management (CRUD)
- RESTful API structure
- Laravel best practices
- Easily extendable and maintainable codebase

---

## ⚙️ Setup Instructions

Follow these steps to set up the project on your local machine.

### 1. Clone the Repository
```bash
git clone https://github.com/bappy3a/Task-Management-API-For-Sofof-Tech.git
cd Task-Management-API-For-Sofof-Tech
```
### 2. Create the Database

Create a new MySQL database named:
```bash
task_management
```
### 3. Install Dependencies
```bash
composer install
```

### 4. Configure Environment File
```bash
cp .env.example .env
php artisan key:generate
```
Update the .env file with your database credentials:
```bash
DB_DATABASE=task_management
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Start the Development Server
```bash
php artisan serve
```
Your API will now be available at:
```bash
http://127.0.0.1:8000
```

### API Documentation 

- [GitHub:](https://github.com/bappy3a/Task-Management-API-For-Sofof-Tech)
- [Postman Collection:](https://www.postman.com/bappy3a/workspace/public/collection/14497889-6d3d8e4d-b470-4c79-93bb-5411202af3d9?action=share&creator=14497889)

### Project Structure
```bash
app/
├── Console/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php
│   │   │   └── TaskController.php
│   ├── Middleware/
├── Interfaces/
│   ├── Auth/
│   │   └── AuthServiceInterface.php
│   ├── Task/
│   │   └── TaskServiceInterface.php
├── Models/
│   └── Task.php
├── Providers/
│   └── RepositoryServiceProvider.php
├── Repositories/
│   ├── Auth/
│   │   └── AuthRepository.php
│   ├── Task/
│   │   └── TaskRepository.php
├── Services/
│   ├── Auth/
│   │   └── AuthService.php
│   ├── Task/
│   │   └── TaskService.php
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
