# Task Manager 📝

Hey! Welcome to my **Task Manager**! It's built with a Laravel backend and a Vue.js frontend using Inertia.js. Hope you like it!

## ✨ What It Can Do

*  **Task Management**: Tasks CRUD.
*  **Category Management**: Categories CRUD.
*  **Nested Categories**: Organize categories into parent-child hierarchies
*  **Filtering and Sorting**
*  **Pagination**
*  **Task-Category Linking**
*  **Toast Notifications**
*  **RESTful API**

## 🛠️ Tech I Used

* PHP 8.1+
* Laravel 10
* PostgreSQL 15
* Vue.js 3
* Inertia.js 1.0
* Pinia 2
* Tailwind CSS
* Pest 2

## 🚀 Setup

1.  **Clone the Project:**
    ```bash
    git clone https://github.com/erika-dalmagro/task_manager.git
    cd task_manager
    ```

2.  **Backend Setup (Laravel):**
    * Install PHP dependencies:
        ```bash
        composer install
        ```
    * Create your environment file (.env)
    * Generate your application key:
        ```bash
        php artisan key:generate
        ```
    * **Configure your Database in `.env`:**
        Open the `.env` file and set your database details.
        ```env
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1   # Or your pgsql host
        DB_PORT=5432        # Or your pgsql port
        DB_DATABASE=your_database
        DB_USERNAME=your_user
        DB_PASSWORD=your_password
        ```
    * Run database migrations:
        ```bash
        php artisan migrate
        ```

3.  **Frontend Setup (Vue.js & Vite):**
    ```bash
    npm install
    # OR
    yarn install
    ```

4.  **(Optional) Seed with Sample Data:**
    ```bash
    php artisan db:seed
    ```

## Quick Notes
I tried to keep the code organized:

* **Backend:**
    * **Repositories**: I used a Repository Pattern (`TaskRepository`, `CategoryRepository`). Controllers talk to Services, and Services talk to these Repositories.
    * **Services**: The main "thinking"for tasks and categories is in Service classes (`TaskService`, `CategoryService`).
    * **API Resources**: I'm using Laravel's API Resources (`TaskResource`, `CategoryResource`).
    * **Form Requests**: Check if the data is valid before the controller (e.g., `StoreTaskRequest`).
    * **Soft Deletes**: If you delete a task or category, it's not gone forever right away.
    * **API Routes**: The API routes use `apiResource` for easy setup of CRUD endpoints.

* **Frontend:**
    * **Inertia.js**: Connects Laravel (backend) and Vue (frontend).
    * **Pinia**: Manages shared data (state) in the Vue app. The Pinia stores (`taskStore.ts`, `categoryStore.ts`) also make the calls to the backend API using Axios.
    * **JS Helpers**: Files like `resources/js/Pages/Tasks/taskHelper.js` store things like options for dropdown menus (status, priority, etc.) to keep them organized.

---

Good coding!
