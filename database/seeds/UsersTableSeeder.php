<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        User::truncate();

        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password
        $password = Hash::make('123456');

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => $password,
            'role' => 'Admin',
        ]);

        // And now let's generate a few users for our app:
        for ($i = 0; $i < 10; $i++) {
            $role = ($i%2 == 0) ? 'Teacher' : 'Student';
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
                'role' => $role,
            ]);
        }
    }
}
