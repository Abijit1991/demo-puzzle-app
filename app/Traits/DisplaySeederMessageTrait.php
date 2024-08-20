<?php

namespace App\Traits;

/**
 * DisplaySeederMessageTrait
 *
 * A trait that provides a method to display messages in the console during
 * command-line operations. This trait is particularly useful in Laravel seeder
 * classes or other Artisan commands to give the user feedback or status updates.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.0
 */

trait DisplaySeederMessageTrait
{
    /**
     * Display a message to the command line.
     *
     * This method uses the `info` method of the `$command` property to output
     * a message with informational styling. It is commonly used for providing
     * feedback or status updates during command-line operations.
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
