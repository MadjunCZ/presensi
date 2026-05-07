<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Kegiatan::query()->orderBy('tanggal', 'desc');

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $kegiatans = $query->paginate(10)->withQueryString();

        return view('admin.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
        ]);

        // Generate unique token
        $validated['token'] = Str::uuid()->toString();

        Kegiatan::create($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan): View
    {
        $kegiatan->loadCount('absensis');
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $kegiatan->update($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }

    /**
     * Generate new token for a kegiatan.
     */
    public function regenerateToken(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->update(['token' => Str::uuid()->toString()]);

        return redirect()->back()
            ->with('success', 'Token berhasil digenerate ulang!');
    }

    /**
     * Display list of absensi for a kegiatan.
     */
    public function absensi(Kegiatan $kegiatan, Request $request): View
    {
        $query = $kegiatan->absensis()->orderBy('waktu_absensi', 'desc');

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%')
                  ->orWhere('satker', 'like', '%' . $request->search . '%');
            });
        }

        $absensis = $query->paginate(25)->withQueryString();

        return view('admin.kegiatan.absensi', compact('kegiatan', 'absensis'));
    }

    /**
     * Export absensi to Excel.
     */
    public function exportExcel(Kegiatan $kegiatan): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $fileName = 'absensi_' . Str::slug($kegiatan->nama_kegiatan) . '_' . date('d-m-Y') . '.xlsx';
        
        return Excel::download(new AbsensiExport($kegiatan), $fileName);
    }

    /**
     * Show QR Code modal.
     */
    public function qrcode(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.qrcode', compact('kegiatan'));
    }
}
