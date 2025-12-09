<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $types = ['EVENT', 'ANNOUNCEMENT', 'UPDATE', 'REMINDER'];
        
        $newsItems = [
            ['type' => 'ANNOUNCEMENT', 'title' => 'New SK Registration Period Opens', 'content' => 'We are now accepting new member registrations. Visit the barangay hall from Monday to Friday, 8AM-5PM.'],
            ['type' => 'EVENT', 'title' => 'Upcoming Community Clean-up Drive', 'content' => 'Join us this Saturday for our monthly clean-up drive. Meeting point at Barangay Hall at 6:00 AM.'],
            ['type' => 'REMINDER', 'title' => 'Monthly Meeting Reminder', 'content' => 'All SK members are reminded of our monthly meeting this Friday at 7:00 PM. Attendance is mandatory.'],
            ['type' => 'UPDATE', 'title' => 'New SK Projects Approved', 'content' => 'We are excited to announce that three new projects have been approved for this quarter.'],
            ['type' => 'ANNOUNCEMENT', 'title' => 'SK Portal Launch', 'content' => 'We have launched our new online portal for easier member management and event coordination.'],
            ['type' => 'EVENT', 'title' => 'Basketball Tournament Registration Open', 'content' => 'Register your team now for the inter-purok basketball tournament. Limited slots available!'],
            ['type' => 'REMINDER', 'title' => 'Event Registration Deadline', 'content' => 'Reminder: Registration for the Youth Leadership Seminar closes this Friday.'],
            ['type' => 'UPDATE', 'title' => 'Attendance Policy Update', 'content' => 'Please take note of the updated attendance policy for all SK activities.'],
            ['type' => 'ANNOUNCEMENT', 'title' => 'Scholarship Program Available', 'content' => 'SK members can now apply for educational scholarships. Contact the office for details.'],
            ['type' => 'EVENT', 'title' => 'Mental Health Awareness Week', 'content' => 'Join us for various activities promoting mental health awareness. Schedule posted on the bulletin board.'],
        ];

        foreach ($newsItems as $index => $item) {
            News::create([
                'type' => $item['type'],
                'title' => $item['title'],
                'content' => $item['content'],
                'author' => 'Admin User',
                'published_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'notify_members' => true,
            ]);
        }
    }
}
