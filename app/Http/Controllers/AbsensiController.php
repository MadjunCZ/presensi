<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Display the attendance form for a kegiatan.
     */
    public function index(string $token): View
    {
        $kegiatan = Kegiatan::where('token', $token)->first();

        if (!$kegiatan) {
            return view('absensi.invalid');
        }

        // Check time restrictions
        $now = now();
        $tanggal = Carbon::parse($kegiatan->tanggal);
        $mulai = Carbon::parse($tanggal->toDateString() . ' ' . $kegiatan->jam_mulai);
        $selesai = Carbon::parse($tanggal->toDateString() . ' ' . $kegiatan->jam_selesai);

        // Check if date is in the future (belum mulai)
        if ($now->toDateString() < $tanggal->toDateString()) {
            $selisih = $mulai->diffInMinutes($now);
            return view('absensi.belum_mulai', compact('kegiatan', 'selisih'));
        }

        // Check if date is in the past (sudah selesai)
        if ($now->toDateString() > $tanggal->toDateString()) {
            return view('absensi.sudah_selesai', compact('kegiatan'));
        }

        // Check if not started yet (on the correct date)
        if ($now->lt($mulai)) {
            $selisih = $mulai->diffInMinutes($now);
            return view('absensi.belum_mulai', compact('kegiatan', 'selisih'));
        }

        // Check if already closed
        if ($now->gt($selesai)) {
            return view('absensi.selesai', compact('kegiatan'));
        }

        return view('absensi.index', compact('kegiatan'));
    }

    /**
     * Store a new attendance record.
     */
    public function store(Request $request, string $token): RedirectResponse
    {
        $kegiatan = Kegiatan::where('token', $token)->first();

        if (!$kegiatan) {
            return view('absensi.invalid');
        }

        // Base validation rules
        $rules = [
            'nip' => 'required|numeric|digits:18',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'satker' => 'required|string|max:255',
            'ttd' => 'required|string', // Base64 signature
        ];

        // Tambahkan validasi GPS jika kegiatan menggunakan GPS
        if ($kegiatan->isGpsEnabled()) {
            $rules['latitude_user'] = 'required|numeric|between:-90,90';
            $rules['longitude_user'] = 'required|numeric|between:-180,180';
        }

        $validated = $request->validate($rules);

        // Check if already registered
        $existing = Absensi::where('kegiatan_id', $kegiatan->id)
            ->where('nip', $validated['nip'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'NIP ini sudah terdaftar dalam kegiatan ini!')
                ->withInput();
        }

        // Prepare attendance data
        $absensiData = [
            'kegiatan_id' => $kegiatan->id,
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'satker' => $validated['satker'],
            'ttd' => $validated['ttd'],
            'waktu_absensi' => now(),
        ];

        // Validasi GPS di backend jika kegiatan menggunakan GPS
        if ($kegiatan->isGpsEnabled()) {
            $latUser = (float) $validated['latitude_user'];
            $lngUser = (float) $validated['longitude_user'];
            
            // Hitung jarak menggunakan rumus Haversine
            $jarak = $this->hitungJarakHaversine(
                $kegiatan->latitude, $kegiatan->longitude,
                $latUser, $lngUser
            );

            $statusValidasi = $jarak <= $kegiatan->radius_meter ? 'dalam_radius' : 'luar_radius';

            $absensiData['latitude_user'] = $latUser;
            $absensiData['longitude_user'] = $lngUser;
            $absensiData['jarak_meter'] = round($jarak, 2);
            $absensiData['status_validasi_radius'] = $statusValidasi;

            // Blokir jika di luar radius (validasi backend)
            if ($statusValidasi === 'luar_radius') {
                return redirect()->back()
                    ->with('error', 'Anda berada di luar area absensi! Jarak Anda: ' . round($jarak) . ' meter (maksimal ' . $kegiatan->radius_meter . ' meter)')
                    ->withInput();
            }
        }

        // Create attendance record
        $absensi = Absensi::create($absensiData);

        return redirect()->route('absensi.success')
            ->with('success', 'Absensi berhasil!')
            ->with('kegiatan_id', $kegiatan->id)
            ->with('absensi_id', $absensi->id);
    }

    /**
     * Hitung jarak antara dua titik koordinat menggunakan rumus Haversine.
     * 
     * @param float $lat1 Latitude titik pertama
     * @param float $lng1 Longitude titik pertama
     * @param float $lat2 Latitude titik kedua
     * @param float $lng2 Longitude titik kedua
     * @return float Jarak dalam meter
     */
    private function hitungJarakHaversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Display success page.
     */
    public function success(): View
    {
        return view('absensi.success');
    }

    /**
     * Display invalid token page.
     */
    public function invalidToken(): View
    {
        return view('absensi.invalid');
    }
}
