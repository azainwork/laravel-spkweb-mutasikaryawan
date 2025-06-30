<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Penilaian - {{ $user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 4px 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Data Penilaian Pegawai: {{ $user->name }}</h2>
    @foreach($penilaiansByYear as $tahun => $penilaians)
        <h4>Penilaian Tahun {{ $tahun }}</h4>
        <p>Rata-rata: {{ number_format($averageByYear[$tahun], 2) }}</p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kriteria</th>
                    <th>Sub Kriteria</th>
                    <th>Nilai</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilaians as $index => $penilaian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penilaian->kriteria->nama }}</td>
                    <td>{{ $penilaian->subKriteria->nama }}</td>
                    <td>{{ $penilaian->nilai }}</td>
                    <td>{{ $penilaian->catatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
