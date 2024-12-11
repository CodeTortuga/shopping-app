# Shopping App

This is a **Laravel** and **Vue.js** application for managing shopping lists. It allows users to create shopping lists, add items to the lists, update or delete items, and track their shopping.

---

## Features
- Manage shopping lists.
- Add, update, and remove items.
- Track total cost and remaining budget.
- Mark items as "picked up."

---

## Technologies Used

- **Backend**: Laravel (PHP Framework)
- **Frontend**: Vue.js (JavaScript Framework)
- **Database**: SQLite (for testing) or MySQL

---

## Setup Instructions

### Installation Steps

1. **Clone the Repository:**
   ```bash
   git clone git@github.com:CodeTortuga/shopping-app.git
   cd shopping-app
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Set Up Environment:**
   Copy the example environment file and update it as needed:
   ```bash
   cp .env.example .env
   ```
    - Update database credentials (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in the `.env` file.

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations:**
   ```bash
   php artisan migrate
   ```

6. **Run the Development Server:**
   ```bash
   php artisan serve
   npm run dev
   ```
  

---

## API Endpoints

### Shopping List Endpoints

- **Create a Shopping List**
  ```http
  POST /api/list
  ```
  **Payload:**
  ```json
  {
    "name": "Weekly Groceries",
    "budget": 50.00
  }
  ```

- **Get All Shopping Lists**
  ```http
  GET /api/list/all
  ```

- **Get Shopping List with Items**
  ```http
  GET /api/list/{listId}
  ```

### Shopping List Items Endpoints

- **Get All Items**
  ```http
  GET /api/items
  ```

- **Create an Item**
  ```http
  POST /api/items
  ```
  **Payload:**
  ```json
  {
    "name": "Eggs",
    "price": 2.50,
    "quantity": 12,
    "picked_up": false,
    "shopping_list_id": 1
  }
  ```

- **Update an Item**
  ```http
  PUT /api/items/{id}
  ```
  **Payload:**
  ```json
  {
    "name": "Ostrich Eggs",
    "price": 3.00,
    "quantity": 10
  }
  ```

- **Delete an Item**
  ```http
  DELETE /api/items/{id}
  ```

- **Toggle Picked Up Status**
  ```http
  PATCH /api/items/{id}/toggle-picked-up
  ```

---

## Testing

### Setting Up Tests

1. **Run Migrations for Testing:**
   ```bash
   php artisan migrate --env=testing
   ```

2. **Run Unit Tests:**
   ```bash
   php artisan test
   ```

---

## Notes

- This project includes attempts at implementing item reordering, but it is currently not functional. Did not have enough time.
- Ensure proper database setup for testing (SQLite is configured for testing in `phpunit.xml`).
