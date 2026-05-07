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

        $validated = $request->validate([
            'nip' => 'required|numeric|digits:18',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'satker' => 'required|string|max:255',
            'ttd' => 'required|string', // Base64 signature
        ]);

        // Check if already registered
        $existing = Absensi::where('kegiatan_id', $kegiatan->id)
            ->where('nip', $validated['nip'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'NIP ini sudah terdaftar dalam kegiatan ini!')
                ->withInput();
        }

        // Create attendance record
        $absensi = Absensi::create([
            'kegiatan_id' => $kegiatan->id,
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'satker' => $validated['satker'],
            'ttd' => $validated['ttd'],
            'waktu_absensi' => now(),
        ]);

        return redirect()->route('absensi.success')
            ->with('success', 'Absensi berhasil!')
            ->with('kegiatan_id', $kegiatan->id)
            ->with('absensi_id', $absensi->id);
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
