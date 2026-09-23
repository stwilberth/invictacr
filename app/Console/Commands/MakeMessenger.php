<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeMessenger extends Command
{
    protected $signature = 'messenger:make {email}';
    protected $description = 'Assign the mensajero role to an existing user by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return self::FAILURE;
        }

        if ($user->is_admin) {
            $this->error('An administrator cannot be changed to mensajero with this command.');
            return self::FAILURE;
        }

        $user->update(['role' => 'mensajero']);
        $this->info("User {$email} is now a mensajero.");

        return self::SUCCESS;
    }
}
