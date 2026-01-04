# HomeHeal 🏥

HomeHeal is a digital platform connecting physical therapists with patients to improve recovery outcomes through remote monitoring and digital protocol management.

## 🚀 Getting Started Area

Follow these instructions to set up the project on your local machine using XAMPP.

### Prerequisites
1.  **XAMPP**: Download and install from [apachefriends.org](https://www.apachefriends.org/).
2.  **Composer**: Dependency manager for PHP.
3.  **Node.js & NPM**: For frontend assets.
4.  **Git**: To clone the repository.

### 📥 Installation Steps

1.  **Start XAMPP Servers**
    *   Open **XAMPP Control Panel**.
    *   Click **Start** next to **Apache**.
    *   Click **Start** next to **MySQL**.
    *   *Ensure both turn green.*

2.  **Create the Database**
    *   Open your browser and go to `http://localhost/phpmyadmin`.
    *   Click **New** in the sidebar.
    *   Database name: `homeheal`
    *   Click **Create**.

3.  **Clone the Repository**
    Open your terminal (Command Prompt or PowerShell) and navigate to where you want the project:
    ```bash
    git clone <repository-url>
    cd HomeHeal
    ```

4.  **Install Backend Dependencies**
    ```bash
    composer install
    ```

5.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

6.  **Environment Setup**
    Copy the example environment file:
    ```bash
    cp .env.example .env
    ```
    *Open the `.env` file in a text editor (like VS Code or Notepad) and check strict settings:*
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=homeheal
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *(Note: XAMPP default MySQL password is typically empty, so leave `DB_PASSWORD=` blank unless you changed it).*

7.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

8.  **Setup Database Tables & Data**
    Run migrations and seed the database with test data:
    ```bash
    php artisan migrate --seed
    ```

9.  **Create Storage Link**
    ```bash
    php artisan storage:link
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
