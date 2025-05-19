<?php

namespace App\Console\Commands;

use Filament\Commands\MakeUserCommand;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

//#[AsCommand(name: 'make:filament-create-user')]
class CreateUser extends MakeUserCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filament-user
                            {--name= : The name of the user}
                            {--username= : A valid and unique username}
                            {--password= : The password for the user (min. 8 characters)}
                            {--password2= : The password2 for the user (min. 8 characters)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User';

    /**
     * @var array{'name': string | null, 'username': string | null, 'password': string | null}
     */
    protected array $options;

    /**
     * @return array{'name': string, 'username': string, 'password': string}
     */

    /**
     * Ask For Arguments
     */
//    protected function promptForMissingArgumentsUsing(): array
//    {
//        return [
//            'username' => 'Username',
//        ];
//    }

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
                        static::getUserModel()::where('username', $username)->exists() => 'A username already exists',
                        default => null,
                    },
                ),

            'password' => Hash::make($this->options['password'] ?? password(
                label: 'Password',
                required: true,
            )),
        ];
    }

    protected function sendSuccessMessage(Authenticatable $user): void
    {
        $loginUrl = Filament::getLoginUrl();

        $this->components->info('Success! ' . ($user->getAttribute('username') ?? $user->getAttribute('username') ?? 'You') . " may now log in at {$loginUrl}");
    }

}
