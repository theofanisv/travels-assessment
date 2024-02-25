<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserToken extends Command
{
    protected $signature = 'user:create-token {email}';

    protected $description = 'Create Sanctum token for the specified user (email or id).';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::orWhere(['email' => $email, 'id' => $email])->firstOrFail();
        $token = $user->createToken('via-command');
        
        $this->line("Created token for user {$user->email} with role {$user->role->name->name}: ");
        $this->info($token->plainTextToken);
    }
}
