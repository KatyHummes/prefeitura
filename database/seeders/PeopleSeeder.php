<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            do {
                $cpf = mt_rand(10000000000, 99999999999);
            } while (DB::table('people')->where('cpf', $cpf)->exists());

            DB::table('people')->insert([
                'name' => $faker->name,
                'birth' => $faker->date,
                'sex' => $faker->randomElement(['Masculino', 'Feminino', 'Outro']),
                'cpf' => $cpf,
                'city' => $faker->city,
                'neighborhood' => $faker->city,
                'street' => $faker->city,
                'number' => $faker->numberBetween(1, 1000),
                'complement' => $faker->city(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
