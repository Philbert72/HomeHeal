# HomeHeal 🏥

HomeHeal is a digital platform connecting physical therapists with patients to improve recovery outcomes through remote monitoring and digital protocol management.

## 🚀 Getting Started Area

Follow these instructions to set up the project on your local machine.

### Prerequisites
Ensure you have the following installed:
- **PHP** (8.2 or higher)
- **Composer** (Dependency manager for PHP)
- **Node.js** & **NPM** (For frontend assets)
- **MySQL** (Or any other supported database)

### 📥 Installation Steps

1.  **Clone the Repository**
    ```bash
    git clone <repository-url>
    cd HomeHeal
    ```

2.  **Install Backend Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the example environment file and configure it.
    ```bash
    cp .env.example .env
    ```
    *Open the `.env` file in a text editor and update your database settings:*
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=homeheal  # Make sure this database exists in your MySQL
    DB_USERNAME=root      # Your database username
    DB_PASSWORD=          # Your database password
    ```

5.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

6.  **Create the Storage Link**
    ```bash
    php artisan storage:link
    ```

7.  **Setup Database**
    Run migrations and seed the database with test data (Users, Protocols, Exercises).
    ```bash
    php artisan migrate --seed
    ```

---

## 🏃‍♂️ Running the Application

You need to run two separate terminal commands (keep both running):

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```

**Terminal 2 (Frontend compilation):**
```bash
npm run dev
```

Visit `http://localhost:8000` in your browser.

---

## 🔐 Login Credentials (Test Data)

**Therapist Account:**
- **Email**: `therapist@homeheal.com`
- **Password**: `password`

**Patient Account:**
- **Email**: `patient@homeheal.com`
- **Password**: `password`

---

## 🛠 Tech Stack
- **Framework**: Laravel 12
- **Frontend**: Livewire 3.7 + Alpine.js
- **Styling**: Tailwind CSS 4.0
