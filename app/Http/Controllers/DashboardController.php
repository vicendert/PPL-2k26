<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user && $user->isOwner()) {
            return $this->ownerDashboard();
        }

        if ($user && $user->isAdminKasir()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($request);
    }

    private function userDashboard(Request $request)
    {
        $date   = $request->input('date', now()->toDateString());
        $courts = Court::query()->orderBy('name')->get();
        $slots  = $this->buildSlots();

        // Booking semua court untuk slot grid (hari yang dipilih)
        $bookings = Booking::query()
            ->with('court')
            ->whereDate('date', $date)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->get();

        $bookingMap = $this->buildBookingMap($bookings);

        // Riwayat booking milik user yang login
        $myBookings = Booking::query()
            ->with('court')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return view('dashboard.user', [
            'date'       => $date,
            'courts'     => $courts,
            'slots'      => $slots,
            'bookingMap' => $bookingMap,
            'myBookings' => $myBookings,
            'pricePerHour' => 300000,
        ]);
    }

    private function adminDashboard()
    {
        $users = User::query()->orderBy('created_at', 'desc')->get();
        $bookings = Booking::query()
            ->with(['user', 'court'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(50)
            ->get();

        return view('dashboard.admin', [
            'users'    => $users,
            'bookings' => $bookings,
        ]);
    }

    private function ownerDashboard()
    {
        $users = User::query()->orderBy('created_at', 'desc')->limit(12)->get();
        $revenue = Booking::query()
            ->where('status', Booking::STATUS_CONFIRMED)
            ->sum('total_price');

        $activeAdmins = User::query()
            ->where('role', User::ROLE_ADMIN_KASIR)
            ->where('is_online', true)
            ->where('last_active_at', '>=', now()->subMinutes(5))
            ->orderBy('last_active_at', 'desc')
            ->get();

        return view('dashboard.owner', [
            'users'        => $users,
            'revenue'      => $revenue,
            'activeAdmins' => $activeAdmins,
        ]);
    }

    private function buildSlots(): array
    {
        $slots = [];
        $start = Carbon::createFromTime(0, 0);

        for ($i = 0; $i < 24; $i++) {
            $slots[] = $start->format('H:i');
            $start->addHour();
        }

        return $slots;
    }

    private function buildBookingMap($bookings): array
    {
        $map = [];

        foreach ($bookings as $booking) {
            $cursor = Carbon::parse($booking->start_time);
            $end    = Carbon::parse($booking->end_time);

            while ($cursor < $end) {
                $map[$booking->court_id][$cursor->format('H:i')] = $booking->status;
                $cursor->addHour();
            }
        }

        return $map;
    }
}
