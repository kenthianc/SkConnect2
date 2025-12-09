<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            'Community Service',
            'Sports & Recreation',
            'Education & Training',
            'Health & Wellness',
            'Cultural',
            'Social',
        ];

        $events = [
            ['title' => 'Community Clean-up Drive', 'category' => 'Community Service'],
            ['title' => 'Basketball Tournament', 'category' => 'Sports & Recreation'],
            ['title' => 'Youth Leadership Seminar', 'category' => 'Education & Training'],
            ['title' => 'Mental Health Awareness Campaign', 'category' => 'Health & Wellness'],
            ['title' => 'Buwan ng Wika Celebration', 'category' => 'Cultural'],
            ['title' => 'Christmas Party', 'category' => 'Social'],
            ['title' => 'Tree Planting Activity', 'category' => 'Community Service'],
            ['title' => 'Volleyball League', 'category' => 'Sports & Recreation'],
            ['title' => 'First Aid Training', 'category' => 'Health & Wellness'],
            ['title' => 'Talent Show', 'category' => 'Cultural'],
        ];

        foreach ($events as $index => $eventData) {
            $date = $faker->dateTimeBetween('-3 months', '+6 months');
            $status = $date < now() ? 'Completed' : ($date->format('Y-m-d') === now()->format('Y-m-d') ? 'Ongoing' : 'Upcoming');

            Event::create([
                'title' => $eventData['title'],
                'description' => $faker->paragraph(3),
                'category' => $eventData['category'],
                'date' => $date,
                'start_time' => $faker->time('H:i', '10:00'),
                'end_time' => $faker->time('H:i', '17:00'),
                'location' => 'Barangay ' . $faker->streetName(),
                'venue' => $faker->randomElement(['Barangay Hall', 'Covered Court', 'Multi-purpose Hall', 'Community Center']),
                'target_participants' => $faker->numberBetween(30, 100),
                'max_capacity' => $faker->numberBetween(100, 200),
                'registration_deadline' => (clone $date)->modify('-3 days'),
                'status' => $status,
            ]);
        }
    }
}
