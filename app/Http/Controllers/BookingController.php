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
            'date'           => ['required', 'date', 'after_or_equal:today'],
            'start_time'     => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
            'promo_code'     => ['nullable', 'string'],
            'payment_proof'  => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'court_id.required'       => 'Lapangan wajib dipilih.',
            'date.required'           => 'Tanggal booking wajib diisi.',
            'date.after_or_equal'     => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
            'start_time.required'     => 'Jam mulai wajib diisi.',
            'duration_hours.required' => 'Durasi booking wajib diisi.',
            'payment_proof.required'  => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.file'      => 'Bukti pembayaran harus berupa file yang valid.',
            'payment_proof.image'     => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes'     => 'Format gambar bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max'       => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ]);

        $startH  = (int) explode(':', $validated['start_time'])[0];
        $endH    = $startH + (int) $validated['duration_hours'];

        // Pastikan tidak melebihi jam 24
        if ($endH > 24) {
            return back()->withErrors([
                'start_time' => 'Durasi booking melebihi batas jam operasional (max 24:00).',
            ])->withInput();
        }

        $startTime = str_pad($startH, 2, '0', STR_PAD_LEFT) . ':00';
        $endTime   = str_pad($endH,   2, '0', STR_PAD_LEFT) . ':00';

        // Cek konflik jadwal (time overlap check)
        $conflict = Booking::query()
            ->where('court_id', $validated['court_id'])
            ->whereDate('date', $validated['date'])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where(function ($query) use ($startTime, $endTime) {
                // Booking baru [start, end) tumpang tindih dengan existing [start_time, end_time)
                $query->where('start_time', '<', $endTime)
                      ->where('end_time',   '>', $startTime);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'start_time' => 'Jadwal sudah terisi, silakan pilih jam lain.',
            ])->withInput();
        }

        $hours = (int) $validated['duration_hours'];
        $total = $hours * self::PRICE_PER_HOUR;

        if (!empty($request->promo_code)) {
            $promo = \App\Models\Promo::where('code', strtoupper($request->promo_code))
                        ->where('is_active', true)
                        ->first();
            
            if ($promo) {
                $total = $total * (1 - ($promo->discount_percentage / 100));
            } else {
                return back()->withErrors([
                    'promo_code' => 'Kode promo tidak valid atau sudah tidak aktif.',
                ])->withInput();
            }
        }

        $court = Court::query()->findOrFail($validated['court_id']);

        $paymentProofPath = $request->file('payment_proof')->store('payments', 'public');

        Booking::query()->create([
            'user_id'        => $request->user()->id,
            'court_id'       => $court->id,
            'date'           => $validated['date'],
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'duration_hours' => $hours,
            'status'         => Booking::STATUS_PENDING,
            'price_per_hour' => self::PRICE_PER_HOUR,
            'total_price'    => $total,
            'payment_proof'  => $paymentProofPath,
        ]);

        return back()->with('success', 'Booking berhasil dibuat dan bukti pembayaran telah diunggah! Menunggu konfirmasi admin.');
    }

    /**
     * Tampilkan halaman khusus pencatatan pesanan walk-in untuk kasir.
     */
    public function walkIn()
    {
        $courts = Court::query()->orderBy('name')->get();

        $walkInBookings = Booking::query()
            ->with(['court', 'user'])
            ->where(function ($query) {
                $query->whereNotNull('customer_name')
                      ->orWhere('notes', 'like', '%Walk-in%');
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('dashboard.walkin', [
            'courts'         => $courts,
            'walkInBookings' => $walkInBookings,
        ]);
    }

    /**
     * Kasir membuat booking atas nama pelanggan langsung (walk-in order).
     * Booking langsung berstatus confirmed karena kasir yang memverifikasi.
     */
    public function storeByKasir(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'court_id'       => ['required', 'exists:courts,id'],
            'date'           => ['required', 'date', 'after_or_equal:today'],
            'start_time'     => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:8'],
        ], [
            'customer_name.required'  => 'Nama pelanggan wajib diisi.',
            'court_id.required'       => 'Lapangan wajib dipilih.',
            'date.required'           => 'Tanggal booking wajib diisi.',
            'date.after_or_equal'     => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
            'start_time.required'     => 'Jam mulai wajib diisi.',
            'duration_hours.required' => 'Durasi wajib diisi.',
        ]);

        $startH = (int) explode(':', $validated['start_time'])[0];
        $endH   = $startH + (int) $validated['duration_hours'];

        if ($endH > 24) {
            return back()->withErrors([
                'start_time' => 'Durasi booking melebihi batas jam operasional (max 24:00).',
            ])->withInput();
        }

        $startTime = str_pad($startH, 2, '0', STR_PAD_LEFT) . ':00';
        $endTime   = str_pad($endH,   2, '0', STR_PAD_LEFT) . ':00';

        // Cek konflik jadwal
        $conflict = Booking::query()
            ->where('court_id', $validated['court_id'])
            ->whereDate('date', $validated['date'])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time',   '>', $startTime);
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

        // Cari user pelanggan berdasarkan nama (walk-in: gunakan akun kasir sendiri jika tidak ditemukan)
        $customer = \App\Models\User::where('name', $validated['customer_name'])
                        ->where('role', \App\Models\User::ROLE_USER)
                        ->first();
        $userId = $customer?->id ?? $request->user()->id;

        Booking::query()->create([
            'user_id'        => $userId,
            'court_id'       => $court->id,
            'customer_name'  => $validated['customer_name'],
            'date'           => $validated['date'],
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'duration_hours' => $hours,
            // Kasir langsung confirmed karena bayar di tempat
            'status'         => Booking::STATUS_CONFIRMED,
            'price_per_hour' => self::PRICE_PER_HOUR,
            'total_price'    => $total,
            'notes'          => 'Walk-in — dicatat oleh kasir.'
                                . ($customer ? '' : ' (tamu/non-member)'),
        ]);

        return back()->with('success',
            'Booking atas nama "' . $validated['customer_name'] . '" berhasil dibuat dan langsung dikonfirmasi.'
        );
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

    /**
     * API: Kembalikan slot terisi per lapangan untuk tanggal tertentu (JSON).
     * Digunakan oleh frontend agar slot grid dapat diperbarui tanpa reload halaman.
     */
    public function getSlots(Request $request)
    {
        $today = now()->toDateString();
        $date  = $request->input('date', $today);
        if ($date < $today) {
            $date = $today;
        }

        $bookings = Booking::query()
            ->whereDate('date', $date)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->get(['court_id', 'start_time', 'end_time', 'status']);

        // Bangun map: { court_id: { "HH:MM": status, ... }, ... }
        $map = [];
        foreach ($bookings as $booking) {
            // Ambil jam saja (DB simpan "HH:MM:SS", kita butuh integer)
            $startH = (int) substr($booking->start_time, 0, 2);
            $endH   = (int) substr($booking->end_time,   0, 2);
            $endM   = (int) substr($booking->end_time,   3, 2);

            // Jika end punya menit sisa, bulatkan ke atas
            if ($endM > 0) $endH++;

            for ($h = $startH; $h < $endH; $h++) {
                $slot = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                $map[$booking->court_id][$slot] = $booking->status;
            }
        }

        return response()->json($map);
    }
}
