<?php

namespace Webaccess\BiometLaravel\Commands;

use Illuminate\Console\Command;
use Webaccess\BiometLaravel\Models\User;
use Webaccess\BiometLaravel\Services\UserManager;

class CreateUserCommand extends Command
{
    protected $signature = 'biomet:create-user';

    protected $description = 'Créer un utilisateur avec un profil administrateur';

    public function handle()
    {
        $firstName = $this->ask('Entrez le prénom de l\'utilisateur');
        $lastName = $this->ask('Entrez le nom de l\'utilisateur');
        $email = $this->ask('Entrez l\'email de l\'utilisateur');
        $password = $this->secret('Entrez le mot de passe de l\'utilisateur');

        try {
            if (UserManager::createUser($firstName, $lastName, $email, $password, null, User::PROFILE_ID_ADMINISTRATOR))
                $this->info('L\'utilisateur a été créé avec succès');
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue lors de l\'ajout de l\'utilisateur : ' . $e->getMessage());
        }
    }
}
