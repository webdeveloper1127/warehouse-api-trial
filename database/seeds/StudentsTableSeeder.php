<?php

use Illuminate\Database\Seeder;
use App\Student;
class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Student::truncate();

        $faker = \Faker\Factory::create();
        
        // And now let's generate a few students for our app:
        $students = App\User::where('role', 'Student')->get();
        foreach ($students as $student) {
        	Student::create([
                'address' => $faker->city,                
                'user_id' => $student->id,
            ]);		    
		}
    }
}
