<?php

namespace App\Traits;

/**
 * DisplaySeederMessageTrait
 *
 * Displays message after seeding execution.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
trait DisplaySeederMessageTrait
{
    /**
     * Display a message to the command line.
     *
     * @param string $message The message to be displayed.
     *
     * @return void
     */
    public function displayMessage(string $message = null): void
    {
        // Output the message to the command line.
        $this->command->info($message);
    }
}
