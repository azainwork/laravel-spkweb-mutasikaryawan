<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permohonan Mutasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-menunggu { color: #856404; background-color: #fff3cd; }
        .status-diterima { color: #155724; background-color: #d4edda; }
        .status-ditolak { color: #721c24; background-color: #f8d7da; }
        .search-info {
            background-color: #e7f3ff;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERMOHONAN MUTASI PEGAWAI</h1>
        <p>Sistem Informasi Mutasi Pegawai</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if($search)
        <div class="search-info">
            <strong>Hasil Pencarian:</strong> "{{ $search }}"
            <br>
            <strong>Total Data:</strong> {{ $mutasi->count() }} permohonan
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama Pegawai</th>
                <th style="width: 10%;">NIP</th>
                <th style="width: 12%;">Jabatan</th>
                <th style="width: 10%;">Pendidikan</th>
                <th style="width: 20%;">Alasan Mutasi</th>
                <th style="width: 12%;">Lokasi Tujuan</th>
                <th style="width: 10%;">Tanggal Pengajuan</th>
                <th style="width: 6%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mutasi as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->user->nip }}</td>
                    <td>{{ $item->user->jabatan }}</td>
                    <td>{{ $item->pendidikan_terakhir }}</td>
                    <td>{{ Str::limit($item->alasan_mutasi, 100) }}</td>
                    <td>{{ $item->lokasi_tujuan }}</td>
                    <td>{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;">
                        @if($item->status == 'menunggu')
                            <span class="status-menunggu">Menunggu</span>
                        @elseif($item->status == 'diterima')
                            <span class="status-diterima">Diterima</span>
                        @elseif($item->status == 'ditolak')
                            <span class="status-ditolak">Ditolak</span>
                        @else
                            {{ ucfirst($item->status) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">
                        @if($search)
                            Tidak ada data yang sesuai dengan pencarian "{{ $search }}"
                        @else
                            Tidak ada data permohonan mutasi
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Total Data: {{ $mutasi->count() }} permohonan</p>
    </div>
</body>
</html>
