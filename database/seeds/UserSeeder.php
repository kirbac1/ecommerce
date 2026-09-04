<?php

namespace database\seeds;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Demo accounts. All of them use the password 'test' -- change or remove
        // these before putting the app anywhere other than a local machine.
        $users = [
            [
                'name' => 'Store',
                'surname' => 'Owner',
                'email' => 'admin@example.com',
                'type' => 'admin',
                'language' => 'en',
                'superAdmin' => true,
            ],
            [
                'name' => 'Kaupan',
                'surname' => 'Hoitaja',
                'email' => 'manager@example.com',
                'type' => 'admin',
                'language' => 'fi',
                'superAdmin' => true,
            ],
            [
                'name' => 'Store',
                'surname' => 'Clerk',
                'email' => 'cashier@example.com',
                'type' => 'cashier',
                'language' => 'fi',
                'superAdmin' => false,
            ],
        ];

        foreach ($users as $user) {
            User::create($user + ['enabled' => true, 'password' => 'test']);
        }
    }
}
