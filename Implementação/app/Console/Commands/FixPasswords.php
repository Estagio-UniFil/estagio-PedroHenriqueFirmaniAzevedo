<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixPasswords extends Command
{
    protected $signature = 'fix:passwords';
    protected $description = 'Corrige as senhas não criptografadas no banco de dados';

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (!Hash::needsRehash($user->password)) {
                continue; // Senha já está criptografada
            }
            $user->password = Hash::make($user->password);
            $user->save();
            $this->info("Senha do usuário {$user->email} foi corrigida.");
        }
        
        $this->info('Todas as senhas foram corrigidas.');
    }
}
