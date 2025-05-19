<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Filament\Commands\MakeUserCommand;

use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class CreateUserCommand extends MakeUserCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filament-user
                            {--name= : The name of the user}
                            {--username= : A valid and unique username}
                            {--password= : The password for the user (min. 8 characters)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected function getUserData(): array
    {
        return [
            'name' => $this->options['name'] ?? text(
                label: 'Name',
                required: true,
            ),

            'username' => $this->options['username'] ?? text(
                label: 'Username',
                required: true,
                validate: fn (string $username): ?string => match (true) {
                    static::getUserModel()::where('username', $username)->exists() => 'A user with this email address already exists',
                    default => null,
                },
            ),

            'password' => Hash::make($this->options['password'] ?? password(
                label: 'Password',
                required: true,
            )),
        ];
    }
}
