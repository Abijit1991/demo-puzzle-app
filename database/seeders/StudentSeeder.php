<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Traits\DisplaySeederMessageTrait;

class StudentSeeder extends Seeder
{
    use DisplaySeederMessageTrait;

    public $maxUserCreateLimit;
    public $baseUserName;
    public $password;
    public $roleId;
    public $displayMessage;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->maxUserCreateLimit = 20;
        $this->baseUserName = 'demouser';
        $this->password = 'Demo@123';
        $this->roleId = $this->getRoleId();
        $this->displayMessage = $this->getDefaultSuccessMessage();

        foreach (range(1, $this->maxUserCreateLimit) as $index) {
            $username = $this->generateStudentUserName($index);

            try {
                $this->createOrUpdateData($username);
            } catch (\Exception $e) {
                $this->displayMessage = $this->handleException($e);
            }
        }

        // Display a message.
        $this->displayMessage(config('constants.SEEDER_SUCCESS_MSG.STUDENT_SEEDER'));
    }

    /**
     * Get the role ID based on role slug value.
     *
     * @return int|null Return the role id based on slug value if exists otherwise null.
     */
    public function getRoleId(): ?int
    {
        return Role::where('slug', config('constants.ROLES.STUDENT'))->value('id');
    }

    /**
     * Retrieves the default success message from the configuration.
     *
     * @return string The success message.
     */
    public function getDefaultSuccessMessage(): string
    {
        return config('constants.SEEDER_SUCCESS_MSG.STUDENT_SEEDER');
    }

    /**
     * Generates a unique username by appending a formatted number to the base username.
     *
     * @param int $index The current index.
     *
     * @return string The generated username.
     */
    public function generateStudentUserName(int $index): string
    {
        return $this->baseUserName . $this->getFormattedNumber($index);
    }

    /**
     * Formats a number by padding it with leading zeros to ensure it has two digits.
     *
     * @param int $number The number to be formatted.
     *
     * @return string The formatted number as a two-digit string.
     */
    public function getFormattedNumber(int $number): string
    {
        // Return the formatted number.
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Creates or updates a user record with the provided details.
     *
     * @param string $username The username for the user.
     *
     * @return void
     */
    public function createOrUpdateData(string $username): void
    {
        User::updateOrCreate(
            [
                'email' => trim($username . '@demopuzzle.com')
            ],
            [
                'role_id' => trim($this->roleId),
                'name' => trim(ucfirst($username)),
                'password' => Hash::make(trim($this->password))
            ]
        );
    }

    /**
     * Handles an exception by formatting the error message.
     *
     * @param \Exception $e The exception to handle.
     *
     * @return string The formatted error message.
     */
    public function handleException(\Exception $e): string
    {
        return 'Error: ' . $e->getMessage();
    }
}
