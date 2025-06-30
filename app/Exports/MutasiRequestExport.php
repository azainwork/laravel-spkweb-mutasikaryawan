<?php

namespace App\Exports;

use App\Models\MutasiRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MutasiRequestExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = MutasiRequest::with('user');

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nip', 'like', '%' . $this->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $this->search . '%');
            })->orWhere('lokasi_tujuan', 'like', '%' . $this->search . '%')
              ->orWhere('pendidikan_terakhir', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pegawai',
            'NIP',
            'Jabatan',
            'Pendidikan Terakhir',
            'Alasan Mutasi',
            'Lokasi Tujuan',
            'Tanggal Pengajuan',
            'Status',
            'Keterangan',
            'Tanggal Keputusan',
            'Keputusan Akhir'
        ];
    }

    public function map($mutasi): array
    {
        static $no = 1;

        return [
            $no++,
            $mutasi->user->name,
            $mutasi->user->nip,
            $mutasi->user->jabatan,
            $mutasi->pendidikan_terakhir,
            $mutasi->alasan_mutasi,
            $mutasi->lokasi_tujuan,
            $mutasi->tanggal_pengajuan ? \Carbon\Carbon::parse($mutasi->tanggal_pengajuan)->format('d/m/Y') : '-',
            $this->getStatusText($mutasi->status),
            $mutasi->keterangan ?? '-',
            $mutasi->tanggal_keputusan ? \Carbon\Carbon::parse($mutasi->tanggal_keputusan)->format('d/m/Y') : '-',
            $this->getKeputusanText($mutasi->keputusan_akhir)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai'
        ];

        return $statusMap[$status] ?? $status;
    }

    private function getKeputusanText($keputusan)
    {
        $keputusanMap = [
            'belum' => 'Belum',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak'
        ];

        return $keputusanMap[$keputusan] ?? $keputusan;
    }
}
