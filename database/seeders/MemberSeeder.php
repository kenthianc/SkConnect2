<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_PH');

        $puroks = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'];
        $roles = ['Member', 'Member', 'Member', 'Member', 'Officer']; // 80% members, 20% officers

        for ($i = 1; $i <= 50; $i++) {
            $gender = $faker->randomElement(['Male', 'Female']);
            $firstName = $gender === 'Male' ? $faker->firstNameMale() : $faker->firstNameFemale();
            $middleName = $faker->lastName();
            $lastName = $faker->lastName();
            $age = $faker->numberBetween(15, 30);
            $birthdate = now()->subYears($age)->subMonths($faker->numberBetween(0, 11));

            Member::create([
                'member_id' => '25-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@example.com'),
                'phone' => '+639' . $faker->numerify('#########'),
                'birthdate' => $birthdate,
                'age' => $age,
                'gender' => $gender,
                'purok' => $faker->randomElement($puroks),
                'address' => $faker->streetAddress(),
                'guardian_name' => $faker->name(),
                'guardian_contact' => '+639' . $faker->numerify('#########'),
                'role' => $faker->randomElement($roles),
                'registered_via' => $faker->randomElement(['Online', 'Walk-in', 'Event']),
                'date_joined' => $faker->dateTimeBetween('-2 years', 'now'),
                'is_active' => $faker->boolean(90), // 90% active
            ]);
        }
    }
}
