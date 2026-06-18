<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => ['required', 'string', 'max:255', 'unique:promos,code'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);

        Promo::create($validated);

        return back()->with('success', 'Kode promo berhasil ditambahkan.');
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'code'                => ['required', 'string', 'max:255', 'unique:promos,code,' . $promo->id],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);

        $promo->update($validated);

        return back()->with('success', 'Kode promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return back()->with('success', 'Kode promo berhasil dihapus.');
    }

    public function check(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak diberikan.']);
        }

        $promo = Promo::where('code', strtoupper($code))->where('is_active', true)->first();

        if ($promo) {
            return response()->json([
                'valid'               => true,
                'discount_percentage' => $promo->discount_percentage,
            ]);
        }

        return response()->json(['valid' => false, 'message' => 'Kode promo tidak valid atau sudah tidak aktif.']);
    }
}
