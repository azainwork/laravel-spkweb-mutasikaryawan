@extends('layouts.app')

@section('title', 'Detail Hasil Akhir')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Detail Hasil Akhir Pegawai</h4>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.hasil-akhir.index') }}" class="btn btn-secondary mb-3">&larr; Kembali ke Daftar Hasil Akhir</a>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Pegawai</th>
                    <td>{{ $hasilAkhir->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $hasilAkhir->user->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>{{ $hasilAkhir->tahun ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nilai Akhir</th>
                    <td><span class="fw-bold">{{ number_format($hasilAkhir->nilai_akhir, 3) }}</span></td>
                </tr>
                <tr>
                    <th>Ranking</th>
                    <td><span class="badge bg-info fs-5">{{ $hasilAkhir->ranking_akhir }}</span></td>
                </tr>
                <tr>
                    <th>Lokasi Mutasi</th>
                    <td>
                        @if($hasilAkhir->lokasi_mutasi == 'Terdekat')
                            <span class="badge bg-success">Terdekat</span>
                        @elseif($hasilAkhir->lokasi_mutasi == 'Luar Kota')
                            <span class="badge bg-danger">Luar Kota</span>
                        @else
                            <span class="badge bg-warning text-dark">Sesuai Kebijakan</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($hasilAkhir->status == 'menunggu')
                            <span class="badge bg-secondary">Menunggu</span>
                        @elseif($hasilAkhir->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($hasilAkhir->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-light text-dark">{{ ucfirst($hasilAkhir->status) }}</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection 