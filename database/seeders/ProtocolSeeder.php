<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProtocolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = DB::table('people')->pluck('id')->toArray();

        foreach (range(1, 500) as $index) {

            $timestamps = $faker->date();
            
            DB::table('protocols')->insert([
                'people_id' => $faker->randomElement($userIds),
                'description' => $faker->text,
                'term' => $faker->date('Y-m-d'),
                'date' => $faker->date('Y-m-d'),
                'created_at' => $timestamps,
                'updated_at' => $timestamps,
            ]);
        }
    }
}
