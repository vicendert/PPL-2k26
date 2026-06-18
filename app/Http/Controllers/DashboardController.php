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

        $bookings = Booking::query()
            ->with(['user', 'court'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->limit(50)
            ->get();

        $promos = \App\Models\Promo::orderBy('created_at', 'desc')->get();

        return view('dashboard.owner', [
            'users'        => $users,
            'revenue'      => $revenue,
            'activeAdmins' => $activeAdmins,
            'bookings'     => $bookings,
            'promos'       => $promos,
        ]);
    }

    private function buildSlots(): array
    {
        $slots = [];
        for ($i = 0; $i < 24; $i++) {
            $slots[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }
        return $slots;
    }

    private function buildBookingMap($bookings): array
    {
        $map = [];

        foreach ($bookings as $booking) {
            // start_time & end_time dari DB bisa berformat "HH:MM:SS" atau "HH:MM"
            // Ambil hanya jam:menit agar konsisten
            $startStr = substr($booking->start_time, 0, 5); // "HH:MM"
            $endStr   = substr($booking->end_time,   0, 5); // "HH:MM"

            $startH = (int) explode(':', $startStr)[0];
            $endH   = (int) explode(':', $endStr)[0];
            $endM   = (int) explode(':', $endStr)[1];

            // Jika end_time punya menit > 0, bulatkan ke atas
            if ($endM > 0) $endH++;

            for ($h = $startH; $h < $endH; $h++) {
                $slot = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                $map[$booking->court_id][$slot] = $booking->status;
            }
        }

        return $map;
    }
}
