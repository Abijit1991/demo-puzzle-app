# Puzzle Management Application

## Overview

Create a puzzle management system with the following requirements:
1. Each puzzle is defined by a random string of letters that is presented to the user, for example "dgeftoikbvxuaa". The string is guaranteed to allow the construction of at least one valid English word.
2. Students attempt to create English words using the letters provided in the string. Each letter used scores one point. For example fox would score 3 points.
3. Letters can only be used as many times as they appear in the string. Once a letter is used in a submitted word, it cannot be used in subsequent resubmissions by the same student. If a student used fox they would have dgetikbvuaa left to play with.
4. A word has to be a valid English word, consider how this would be validated.
5. When there are no characters left in the string, or the student chooses to end the test, the system will show them their score, and if there were any valid words remaining in the string.
6. The game maintains the top ten highest-scoring submissions (words and score). Anyone using the system should be able to request this list.
7. Duplicate words are not allowed in the high score list. A word can only appear once in the high score list.

## Requirements

- PHP version: **>= 8.1**
- Laravel version: **>= 10**
- Database: **MySQL/PostgreSQL**

## Installation

Follow these steps to get the project up and running on your local machine:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/Abijit1991/demo-puzzle-app.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd demo-puzzle-app
    ```

3. **Install dependencies & dump the autoload files:**

    ```bash
    composer install
    npm install
    npm run build
    composer dump-autoload
    ```

4. **Copy the example environment file:**

    ```bash
    cp .env.example .env
    ```

5. **Generate the application key:**

    ```bash
    php artisan key:generate
    ```

6. **Set up the database:**
    - Create a new database in MySQL/PostgreSQL.
    - Update your `.env` file with the database credentials.

7. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

8. **Seed the database:**

    ```bash
    php artisan db:seed
    ```

9. **Start the development server:**

    ```bash
    php artisan serve
    ```

10. **Access the application:**

    Open your browser and go to `http://localhost:8000`.

## Database Schema

### 1. Puzzles

**Description:** Stores information about puzzles.

**Columns:**
- **id** (integer, primary key, auto-increment): Unique identifier for the puzzle.
- **puzzle_word** (string): The word used in the puzzle.
- **created_at** (timestamp): When the record was created.
- **updated_at** (timestamp): When the record was last updated.

### 2. Puzzle Responses

**Description:** Stores responses submitted by users for puzzles.

**Columns:**
- **id** (integer, primary key, auto-increment): Unique identifier for the response.
- **puzzle_id** (integer, foreign key): References the `id` column in the `puzzles` table.
- **user_id** (integer, foreign key): References the `id` column in the `users` table.
- **response** (string): The user's response to the puzzle.
- **is_valid** (boolean): Indicates whether the response is valid.
- **score** (integer): Score associated with the response.
- **remaining_puzzle_word** (string): The remaining puzzle word after the response.
- **created_at** (timestamp): When the record was created.
- **updated_at** (timestamp): When the record was last updated.

### 3. Users

**Description:** Stores user information.

**Columns:**
- **id** (integer, primary key, auto-increment): Unique identifier for the user.
- **name** (string): The name of the user.
- **email** (string, unique): The email address of the user.
- **password** (string): The hashed password of the user.
- **role_id** (integer, foreign key): References the `id` column in the `roles` table.
- **created_at** (timestamp): When the record was created.
- **updated_at** (timestamp): When the record was last updated.

### 4. Roles

**Description:** Stores user roles.

**Columns:**
- **id** (integer, primary key, auto-increment): Unique identifier for the role.
- **name** (string): The name of the role.
- **slug** (string): A URL-friendly version of the role name.
- **created_at** (timestamp): When the record was created.
- **updated_at** (timestamp): When the record was last updated.

## Contributing

If you want others to contribute to your project, include guidelines for how to contribute. For example:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.

## License

Specify the license under which the project is distributed. For example:

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements

Include any credits, references, or resources that were helpful in building the project.

---

Feel free to customize this template based on your project's specifics!
