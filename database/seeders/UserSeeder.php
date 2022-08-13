<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'roles' => [1, 2],
                'password' => '87654321'
            ],
            [
                'name' => 'Moder',
                'email' => 'moder@gmail.com',
                'roles' => [2],
                'password' => '12345678'
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'roles' => [3],
                'password' => '12345678'
            ],
            [
                'name' => 'User1',
                'email' => 'user1@gmail.com',
                'roles' => [3],
                'password' => '12345678'
            ]
        ];


        foreach ($users as $item) {
            DB::table('users')->insert([
                'name' => $item['name'],
                'email' => $item['email'],
                'password' => bcrypt($item['password'])
            ]);

            $user = (new User())
                ->where('email', $item['email'])
                ->first();

            $user->roles()->sync($item['roles']);
        }
    }
}
