<?php

namespace Webaccess\BiometLaravel\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use Webaccess\BiometLaravel\Models\User;
use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\FacilityManager;
use Webaccess\BiometLaravel\Services\UserManager;

class GenerateDatabaseDataCommand extends Command
{
    protected $signature = 'biomet:generate-database-data';

    protected $description = 'Peuple la base de données avec des données d\'exemple';

    public function handle()
    {
        $faker = Factory::create();

        for ($i = 0; $i < rand(3, 6); $i++) {
            $clientID = ClientManager::createClient($faker->company);

            $nbUsers = rand(3, 6);
            for ($j = 0; $j < $nbUsers; $j++) {
                UserManager::createUser(
                    $faker->firstName,
                    $faker->lastName,
                    $faker->email,
                    $faker->password,
                    $clientID,
                    User::PROFILE_ID_CLIENT
                );
            }

            $nbFacilities = rand(1, 8);
            for ($k = 0; $k < $nbFacilities; $k++) {
                FacilityManager::createFacility(
                    $faker->company,
                    $faker->longitude,
                    $faker->latitude,
                    $faker->address,
                    $faker->city,
                    '',
                    $clientID
                );
            }
        }
        $this->info('Informations insérées avec succès dans la base de données');
    }
}
