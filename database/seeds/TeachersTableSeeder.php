<?php

use Illuminate\Database\Seeder;
use App\Teacher;
class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Teacher::truncate();

        $faker = \Faker\Factory::create();
        
        // And now let's generate a few teachers for our app:
        $teachers = App\User::where('role', 'Teacher')->get();
        
        foreach ($teachers as $teacher) {
        	Teacher::create([
                'address' => $faker->city,                
                'user_id' => $teacher->id,
            ]);		    
		}
    }
}
