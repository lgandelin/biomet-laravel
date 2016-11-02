<?php

namespace Webaccess\BiometLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\User;

class CreateUserCommand extends Command
{
    protected $signature = 'biomet:create-user';

    protected $description = 'Créer un utilisateur';

    public function handle()
    {
        $firstName = $this->ask('Entrez le prénom de l\'utilisateur');
        $lastName = $this->ask('Entrez le nom de l\'utilisateur');
        $email = $this->ask('Entrez l\'email de l\'utilisateur');
        $password = $this->secret('Entrez le mot de passe de l\'utilisateur');

        try {
            if ($this->createUser($firstName, $lastName, $email, $password))
                $this->info('L\'utilisateur a été créé avec succès');
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue lors de l\'ajout de l\'utilisateur : ' . $e->getMessage());
        }
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @return User
     */
    private function createUser($firstName, $lastName, $email, $password)
    {
        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);

        return $user->save();
    }
}
