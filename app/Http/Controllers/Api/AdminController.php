<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Special;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_venues' => Venue::count(),
            'total_specials' => Special::count(),
            'active_events' => Event::where('status', 'upcoming')->count(),
            'active_specials' => Special::where('is_active', true)->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_events' => Event::with('venue')->latest()->take(5)->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Get all users with pagination
     */
    public function getUsers(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate(10);
    }

    /**
     * Get specific user details
     */
    public function getUser($id)
    {
        $user = User::with(['events' => function($query) {
            $query->latest();
        }])->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update user status
     */
    public function updateUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => $request->is_active]);
        return response()->json($user);
    }

    /**
     * Get all events with filters
     */
    public function getEvents(Request $request)
    {
        echo(10);
        $query = Event::with('venue');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Get specific event details
     */
    public function getEvent($id)
    {
        return Event::with(['venue', 'user'])->findOrFail($id);
    }

    /**
     * Update event status
     */
    public function updateEventStatus(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => $request->status]);
        return response()->json($event);
    }

    /**
     * Delete event
     */
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    /**
     * Get all venues with filters
     */
    public function getVenues(Request $request)
    {
        $query = Venue::query();
        
        if ($request->has('type')) {
            $query->where('venue_type', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Get specific venue details
     */
    public function getVenue($id)
    {
        return Venue::with(['events' => function($query) {
            $query->latest();
        }])->findOrFail($id);
    }

    /**
     * Update venue status
     */
    public function updateVenueStatus(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update(['is_available' => $request->is_available]);
        return response()->json($venue);
    }

    /**
     * Delete venue
     */
    public function deleteVenue($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();
        return response()->json(['message' => 'Venue deleted successfully']);
    }

    /**
     * Get all specials with filters
     */
    public function getSpecials(Request $request)
    {
        $query = Special::query();
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('establishment_name', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Get specific special details
     */
    public function getSpecial($id)
    {
        return Special::findOrFail($id);
    }

    /**
     * Update special status
     */
    public function updateSpecialStatus(Request $request, $id)
    {
        $special = Special::findOrFail($id);
        $special->update(['is_active' => $request->is_active]);
        return response()->json($special);
    }

    /**
     * Delete special
     */
    public function deleteSpecial($id)
    {
        $special = Special::findOrFail($id);
        $special->delete();
        return response()->json(['message' => 'Special deleted successfully']);
    }

    /**
     * Get event reports
     */
    public function getEventReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonths(6);
        $endDate = $request->end_date ?? Carbon::now();

        $reports = [
            'total_events' => Event::whereBetween('created_at', [$startDate, $endDate])->count(),
            'events_by_status' => Event::whereBetween('created_at', [$startDate, $endDate])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'events_by_type' => Event::whereBetween('created_at', [$startDate, $endDate])
                ->select('event_type', DB::raw('count(*) as total'))
                ->groupBy('event_type')
                ->get(),
            'monthly_events' => Event::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->get(),
        ];

        return response()->json($reports);
    }

    /**
     * Get user reports
     */
    public function getUserReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonths(6);
        $endDate = $request->end_date ?? Carbon::now();

        $reports = [
            'total_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_users' => User::where('is_active', true)->count(),
            'monthly_registrations' => User::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->get(),
            'users_with_events' => User::whereHas('events')->count(),
        ];

        return response()->json($reports);
    }

    /**
     * Get venue reports
     */
    public function getVenueReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonths(6);
        $endDate = $request->end_date ?? Carbon::now();

        $reports = [
            'total_venues' => Venue::whereBetween('created_at', [$startDate, $endDate])->count(),
            'venues_by_type' => Venue::whereBetween('created_at', [$startDate, $endDate])
                ->select('venue_type', DB::raw('count(*) as total'))
                ->groupBy('venue_type')
                ->get(),
            'available_venues' => Venue::where('is_available', true)->count(),
            'monthly_venues' => Venue::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->get(),
        ];

        return response()->json($reports);
    }

    /**
     * Get special reports
     */
    public function getSpecialReports(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->subMonths(6);
        $endDate = $request->end_date ?? Carbon::now();

        $reports = [
            'total_specials' => Special::whereBetween('created_at', [$startDate, $endDate])->count(),
            'specials_by_type' => Special::whereBetween('created_at', [$startDate, $endDate])
                ->select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),
            'active_specials' => Special::where('is_active', true)->count(),
            'monthly_specials' => Special::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                ->groupBy('month')
                ->get(),
        ];

        return response()->json($reports);
    }

    public function updateSpecial(Request $request, $id)
    {
        $special = \App\Models\Special::findOrFail($id);
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'establishment_name' => 'string|max:255',
            'establishment_type' => 'string|in:restaurant,hotel,cafe,bar,other',
            'location' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'type' => 'in:couple,weekend,valentine,holiday,other',
            'discount_percentage' => 'numeric|min:0|max:100',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
            'terms' => 'nullable|array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
        ]);
        $special->update($validated);
        return $special->fresh();
    }

    public function updateVenue(Request $request, $id)
    {
        $venue = \App\Models\Venue::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'location' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
            'country' => 'string',
            'postal_code' => 'string',
            'venue_type' => 'string',
            'capacity' => 'integer|min:1',
            'rating' => 'numeric|min:0|max:5',
            'amenities' => 'array',
            'images' => 'array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
            'pricing' => 'nullable|array',
            'special_offers' => 'nullable|array',
            'is_available' => 'boolean',
        ]);
        $venue->update($validated);
        return $venue->fresh();
    }
} 