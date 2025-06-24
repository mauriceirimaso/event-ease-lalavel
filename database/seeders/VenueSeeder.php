<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        // 4. Business Conference at Kigali Convention Center (NEW)
        Event::create([
            'name' => 'East Africa Investment Forum',
            'description' => 'Premier gathering for investors and startups in the region',
            'start_time' => Carbon::parse('2024-02-20 08:00:00'),
            'end_time' => Carbon::parse('2024-02-21 18:00:00'),
            'event_type' => 'Business',
            'venue_id' => 1,
            'venue_name' => 'Kigali Convention Center',
            'firebase_user_id' => 'invest_user_004',
            'organizer_name' => 'East Africa Ventures',
            'is_public' => true,
            'expected_attendees' => 800,
            'image_url' => 'investment-forum.jpg',
            'status' => 'upcoming',
            'ticket_price' => 150.00,
            'tags' => ['investment', 'finance', 'startups'],
            'contact_email' => 'register@eainvest.rw',
            'contact_phone' => '+250788121212'
        ]);

        // 5. Culinary Festival at Kigali Serena Hotel (NEW)
        Event::create([
            'name' => 'Rwanda Food & Wine Festival',
            'description' => 'Celebration of Rwandan cuisine and international wines',
            'start_time' => Carbon::parse('2024-03-15 11:00:00'),
            'end_time' => Carbon::parse('2024-03-17 22:00:00'),
            'event_type' => 'Food',
            'venue_id' => 2,
            'venue_name' => 'Kigali Serena Hotel',
            'firebase_user_id' => 'food_user_005',
            'organizer_name' => 'Rwanda Culinary Association',
            'is_public' => true,
            'expected_attendees' => 1200,
            'image_url' => 'food-festival.jpg',
            'status' => 'upcoming',
            'ticket_price' => 40.00,
            'tags' => ['food', 'wine', 'culinary'],
            'contact_email' => 'info@rwandafoodfest.rw',
            'contact_phone' => '+250788343434'
        ]);

        // 6. Wellness Retreat at The Manor Hotel (NEW)
        Event::create([
            'name' => 'Executive Wellness Weekend',
            'description' => 'Luxury wellness program for busy professionals',
            'start_time' => Carbon::parse('2024-04-05 14:00:00'),
            'end_time' => Carbon::parse('2024-04-07 12:00:00'),
            'event_type' => 'Wellness',
            'venue_id' => 3,
            'venue_name' => 'The Manor Hotel',
            'firebase_user_id' => 'wellness_user_006',
            'organizer_name' => 'Rwanda Wellness Collective',
            'is_public' => false,
            'expected_attendees' => 30,
            'image_url' => 'wellness-retreat.jpg',
            'status' => 'upcoming',
            'ticket_price' => 900.00,
            'tags' => ['wellness', 'spa', 'retreat'],
            'contact_email' => 'bookings@rwandawellness.com',
            'contact_phone' => '+250788565656'
        ]);
    }
}