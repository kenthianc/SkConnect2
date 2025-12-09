<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get completed events
        $events = Event::where('status', 'Completed')->get();
        $members = Member::all();

        foreach ($events as $event) {
            // Random 60-90% of members attend each event
            $attendingMembers = $members->random($faker->numberBetween(
                (int)($members->count() * 0.6),
                (int)($members->count() * 0.9)
            ));

            foreach ($attendingMembers as $member) {
                // 85% present, 5% late, 5% excused, 5% absent
                $status = $faker->randomElement([
                    'Present', 'Present', 'Present', 'Present', 'Present', 'Present', 'Present', 'Present', 
                    'Late', 'Excused', 'Absent'
                ]);

                Attendance::create([
                    'event_id' => $event->id,
                    'member_id' => $member->id,
                    'status' => $status,
                    'check_in_time' => $status !== 'Absent' ? $faker->dateTimeBetween($event->date, $event->date . ' +2 hours') : null,
                    'remarks' => $status === 'Excused' ? 'Family emergency' : null,
                ]);
            }
        }
    }
}
