<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Demo accounts. All of them use the password 'test'. They are flagged
        // `demo`, which makes every session read-only (see
        // App\Http\Middleware\PreventDemoWrites), so the credentials are safe to
        // publish. Clear the flag on an account that needs to write.
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
            User::create($user + ['enabled' => true, 'demo' => true, 'password' => 'test']);
        }
    }
}
