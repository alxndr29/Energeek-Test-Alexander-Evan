<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skiils;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');
        for ($i = 0; $i <= 10; $i++) {
            Skiils::create([
                'name' => $faker->lastName,
                'id_hash' => Str::orderedUuid()->toString()
            ]);
        }
    }
}
