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

## Login Credentials

- Email: **demouser01@demopuzzle.com**, **demouser02@demopuzzle.com**, **demouser03@demopuzzle.com**, ..... , **demouser20@demopuzzle.com**
- Password: **Demo@123**

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

**Indexes:**
- **puzzle_id**
- **user_id**
- **is_valid**

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

**Indexes:**
- **role_id**

### 4. Roles

**Description:** Stores user roles.

**Columns:**
- **id** (integer, primary key, auto-increment): Unique identifier for the role.
- **name** (string): The name of the role.
- **slug** (string): A URL-friendly version of the role name.
- **created_at** (timestamp): When the record was created.
- **updated_at** (timestamp): When the record was last updated.

## Controllers

### PuzzleController

The `PuzzleController` handles operations related to puzzles, including displaying puzzle details, saving responses, and showing top scorers.

**Methods:**

#### `__construct()`

- **Purpose:** Ensures that only authenticated users can access the controller's methods.

#### `showPuzzle(Request $request, $id)`

- **Purpose:** Displays the details of a specific puzzle.
- **Parameters:**
  - `$request`: The HTTP request.
  - `$id`: The ID of the puzzle to be displayed.
- **Returns:** A view with the puzzle details and responses.

#### `savePuzzleResponse(Request $request)`

- **Purpose:** Handles the saving of a user's response to a puzzle.
- **Parameters:**
  - `$request`: The HTTP request containing the puzzle ID and user response.
- **Returns:** Redirects to the puzzle details page with updated information.

#### `showPuzzleToppers(Request $request, $id)`

- **Purpose:** Displays the top scorer user details for a specific puzzle.
- **Parameters:**
  - `$request`: The HTTP request.
  - `$id`: The ID of the puzzle to be displayed.
- **Returns:** A view with the top scorers for the specified puzzle.

#### `showToppersList()`

- **Purpose:** Displays the top scorer user details for all puzzles.
- **Returns:** A view with the top scorers for all puzzles.

## Services Class

### PuzzleResponseServices

The `PuzzleResponseServices` class provides helper methods for handling puzzles and their responses. It includes functionality for retrieving puzzle details, validating responses, and calculating top scores.

**Methods:**

#### `getPuzzleResponseDetails($puzzleId)`

- **Purpose:** Retrieves the current puzzle word and its associated responses.
- **Parameters:**
  - `$puzzleId`: The ID of the puzzle to retrieve.
- **Returns:** An array containing the current puzzle word and a collection of puzzle responses.

#### `getPuzzleDetails($puzzleId)`

- **Purpose:** Retrieve the details of a puzzle by its ID.
- **Parameters:**
  - `$puzzleId`: The ID of the puzzle for which responses are retrieved.
- **Returns:** The Puzzle model instance or null if not found.

#### `getValidPuzzleResponseCount($puzzleResponses)`

- **Purpose:** Counts the number of valid puzzle responses from a collection.
- **Parameters:**
  - `$puzzleResponses`: A collection of puzzle responses.
- **Returns:** The number of valid puzzle responses.

#### `savePuzzleResponse($request)`

- **Purpose:** Save a user's response to a puzzle.
- **Parameters:**
  - `$request`: The incoming request.
- **Returns:** No returning.  

#### `getLatestValidRemainingPuzzleWord($puzzleId)`

- **Purpose:** Retrieves the latest valid remaining puzzle word for a given puzzle and user.
- **Parameters:**
  - `$puzzleId`: The ID of the puzzle for which the remaining puzzle word is retrieved.
- **Returns:** The remaining puzzle word from the latest valid response, or null if no valid responses exist.

#### `validateResponse($response)`

- **Purpose:** Validates a response by checking it against an external dictionary API.
- **Parameters:**
  - `$response`: The word or response to be validated.
- **Returns:** True if the response is valid according to the API, false otherwise.

#### `checkPuzzleWordWithResponse($puzzleWord = null, $response = null)`

- **Purpose:** Checks if the given response matches the puzzle word and returns the result.
- **Parameters:**
  - `$puzzleWord`: The puzzle word to be matched against. Defaults to null.
  - `$response`: The response to be checked. Defaults to null.
- **Returns:** An array where the first element is a boolean indicating if the response matches the puzzle word, and the second element is the updated puzzle word.

#### `showPuzzleTopperDetails($puzzleId)`

- **Purpose:** Retrieves details of the top scorers for a given puzzle.
- **Parameters:**
  - `$puzzleId`: The ID of the puzzle for which top scorers are retrieved.
- **Returns:** A collection of top scorers with their user IDs and total scores.

#### `showTopperDetails()`

- **Purpose:** Retrieves details of the top scorers based on valid puzzle responses.
- **Returns:** A collection of top scorers with their user IDs, total scores, and puzzle counts.

## Instructions for Running Unit Tests

Follow these steps to run the unit testing

1. **Perform unit testing for all test scripts:**

    ```bash
    vendor/bin/phpunit
    ```
2. **Perform unit testing to a specific test script:** (replace `<unit_test_script_filename>` with the actual filename)
   ```bash
    vendor/bin/phpunit --filter=<unit_test_script_filename>
    ```
3. **Perform unit testing to a specific test script method:** (replace `<unit_test_script_filename>` & `<test_case_method_name>` with the actual filename and method name)
   ```bash
    vendor/bin/phpunit --filter=<unit_test_script_filename>::<test_case_method_name>
    ```
      

## Acknowledgements

Thanks to the Free Dictionary API - https://dictionaryapi.dev/
