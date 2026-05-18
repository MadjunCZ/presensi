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

        $gpsEnabled = $this->kegiatan->isGpsEnabled();

        return $absensis->map(function ($absensi, $index) use ($gpsEnabled) {
            $row = [
                'No' => $index + 1,
                'NIP' => $absensi->nip,
                'Nama' => $absensi->nama,
                'Jabatan' => $absensi->jabatan,
                'Satker' => $absensi->satker,
                'Waktu Absensi' => $absensi->waktu_formatted,
            ];

            if ($gpsEnabled) {
                $row['Latitude User'] = $absensi->latitude_user;
                $row['Longitude User'] = $absensi->longitude_user;
                $row['Jarak (m)'] = $absensi->jarak_meter;
                $row['Status Radius'] = $absensi->status_validasi_radius === 'dalam_radius' ? 'Dalam Radius' : ($absensi->status_validasi_radius === 'luar_radius' ? 'Di Luar Radius' : '-');
            }

            return $row;
        });
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'NIP',
            'Nama',
            'Jabatan',
            'Satuan Kerja',
            'Waktu Absensi',
        ];

        if ($this->kegiatan->isGpsEnabled()) {
            $headings[] = 'Latitude User';
            $headings[] = 'Longitude User';
            $headings[] = 'Jarak (meter)';
            $headings[] = 'Status Radius';
        }

        return $headings;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 20,
        ];

        if ($this->kegiatan->isGpsEnabled()) {
            $widths['G'] = 15;
            $widths['H'] = 15;
            $widths['I'] = 12;
            $widths['J'] = 15;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $this->kegiatan->isGpsEnabled() ? 'J' : 'F';

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA'],
                ],
            ],
            "A:{$lastCol}" => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
