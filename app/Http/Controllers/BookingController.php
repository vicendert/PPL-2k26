<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Harga per jam: Rp 300.000 (linear)
    public const PRICE_PER_HOUR = 300000;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id'       => ['required', 'exists:courts,id'],
            'date'           => ['required', 'date'],
            'start_time'     => ['required', 'date_format:H:i'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $start   = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end     = (clone $start)->addHours((int) $validated['duration_hours']);
        $endTime = $end->format('H:i');

        // Cek konflik jadwal
        $conflict = Booking::query()
            ->where('court_id', $validated['court_id'])
            ->whereDate('date', $validated['date'])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where(function ($query) use ($validated, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'Jadwal sudah terisi, silakan pilih jam lain.',
            ])->withInput();
        }

        $hours = (int) $validated['duration_hours'];
        $total = $hours * self::PRICE_PER_HOUR;

        $court = Court::query()->findOrFail($validated['court_id']);

        Booking::query()->create([
            'user_id'        => $request->user()->id,
            'court_id'       => $court->id,
            'date'           => $validated['date'],
            'start_time'     => $validated['start_time'],
            'end_time'       => $endTime,
            'duration_hours' => $hours,
            'status'         => Booking::STATUS_PENDING,
            'price_per_hour' => self::PRICE_PER_HOUR,
            'total_price'    => $total,
        ]);

        return back()->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran dan upload bukti bayar.');
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        // Pastikan booking milik user yang login
        abort_if($booking->user_id !== $request->user()->id, 403);
        abort_if($booking->status === Booking::STATUS_CANCELLED, 403, 'Booking telah dibatalkan.');

        $request->validate([
            'payment_proof' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        // Hapus file lama jika ada
        if ($booking->payment_proof && \Storage::disk('public')->exists($booking->payment_proof)) {
            \Storage::disk('public')->delete($booking->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payments', 'public');

        $booking->update(['payment_proof' => $path]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
