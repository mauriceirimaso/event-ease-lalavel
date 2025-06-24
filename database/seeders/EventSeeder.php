<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        Event::create([
            'name' => 'Tech Conference 2023',
            'description' => 'Annual technology summit',
            'start_time' => Carbon::parse('2023-11-15 09:00'),
            'end_time' => Carbon::parse('2023-11-15 17:00'),
            'event_type' => 'Conference',
            'venue_id' => 4, // Must match existing venue ID
            'venue_name' => 'Kigali Convention Center',
            'firebase_user_id' => 'user_tech123',
            'organizer_name' => 'Tech Rwanda',
            'is_public' => true,
            'expected_attendees' => 500,
            'image_url' => 'tech-conf.jpg',
            'status' => 'upcoming',
            'ticket_price' => 50.00,
            'tags' => ['technology', 'innovation'],
            'contact_email' => 'info@techconf.rw',
            'contact_phone' => '+250788123456'
        ]);

        Event::create([
            'name' => 'Wedding Expo',
            'description' => 'Bridal showcase',
            'start_time' => Carbon::parse('2023-12-02 10:00'),
            'end_time' => Carbon::parse('2023-12-03 18:00'),
            'event_type' => 'Exhibition',
            'venue_id' => 5, // Must match existing venue ID
            'venue_name' => 'Kigali Serena Hotel',
            'firebase_user_id' => 'user_wedding456',
            'organizer_name' => 'Wedding Planners RW',
            'is_public' => true,
            'expected_attendees' => 300,
            'image_url' => 'wedding-expo.jpg',
            'status' => 'upcoming',
            'ticket_price' => 20.00,
            'tags' => ['wedding', 'bridal'],
            'contact_email' => 'events@weddings.rw',
            'contact_phone' => '+250788654321'
        ]);
    }
}