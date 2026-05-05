<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\Absensi;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class AbsensiExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $kegiatan;

    public function __construct(Kegiatan $kegiatan)
    {
        $this->kegiatan = $kegiatan;
    }

    public function collection()
    {
        $absensis = $this->kegiatan->absensis()
            ->orderBy('waktu_absensi', 'desc')
            ->get();

        return $absensis->map(function ($absensi, $index) {
            return [
                'No' => $index + 1,
                'NIP' => $absensi->nip,
                'Nama' => $absensi->nama,
                'Jabatan' => $absensi->jabatan,
                'Satker' => $absensi->satker,
                'Waktu Absensi' => $absensi->waktu_formatted,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama',
            'Jabatan',
            'Satuan Kerja',
            'Waktu Absensi',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA'],
                ],
            ],
            'A:F' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
