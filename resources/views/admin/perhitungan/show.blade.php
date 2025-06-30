@extends('layouts.app')

@section('title', 'Detail Perhitungan Oreste')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
                <i class="fas fa-users"></i>
                Data Pegawai
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.kriteria.index') }}">
                <i class="fas fa-cogs"></i>
                Data Kriteria
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.perhitungan.index') }}">
                <i class="fas fa-calculator"></i>
                Perhitungan Oreste
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Perhitungan Oreste</h4>
    <a href="{{ route('admin.perhitungan.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<!-- Informasi Pegawai -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pegawai</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Nama Pegawai</th>
                        <td>: {{ $perhitunganOreste->user->name }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>: {{ $perhitunganOreste->user->nip }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>: {{ $perhitunganOreste->user->jabatan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Tahun</th>
                        <td>: {{ $perhitunganOreste->tahun }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>: {{ $perhitunganOreste->user->pendidikan }}</td>
                    </tr>
                    <tr>
                        <th>Masa Kerja</th>
                        <td>: {{ $perhitunganOreste->user->masa_kerja }} bulan</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Penilaian -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h5>Total Kriteria</h5>
                <h3>{{ $stats['total_kriteria'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5>Kriteria Kinerja</h5>
                <h3>{{ $stats['total_kinerja'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                <h5>Kriteria Kompetensi</h5>
                <h3>{{ $stats['total_kompetensi'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x mb-2"></i>
                <h5>Nilai Rata-rata</h5>
                <h3>{{ number_format(($stats['nilai_rata_kinerja'] + $stats['nilai_rata_kompetensi']) / 2, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Hasil Perhitungan ORESTE -->
<div class="row mb-4">
    <!-- Hasil Kinerja -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Hasil ORESTE - Kinerja</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-success">{{ number_format($perhitunganOreste->nilai_kinerja, 3) }}</h3>
                        <p class="text-muted mb-0">Nilai Kinerja</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-primary">
                            @if($perhitunganOreste->ranking_kinerja == 1)
                                <span class="badge bg-warning fs-4">🥇 {{ $perhitunganOreste->ranking_kinerja }}</span>
                            @elseif($perhitunganOreste->ranking_kinerja == 2)
                                <span class="badge bg-secondary fs-4">🥈 {{ $perhitunganOreste->ranking_kinerja }}</span>
                            @elseif($perhitunganOreste->ranking_kinerja == 3)
                                <span class="badge bg-warning fs-4">🥉 {{ $perhitunganOreste->ranking_kinerja }}</span>
                            @else
                                <span class="badge bg-primary fs-4">{{ $perhitunganOreste->ranking_kinerja }}</span>
                            @endif
                        </h3>
                        <p class="text-muted mb-0">Ranking Kinerja</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Kompetensi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Hasil ORESTE - Kompetensi</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="text-warning">{{ number_format($perhitunganOreste->nilai_kompetensi, 3) }}</h3>
                        <p class="text-muted mb-0">Nilai Kompetensi</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-primary">
                            @if($perhitunganOreste->ranking_kompetensi == 1)
                                <span class="badge bg-warning fs-4">🥇 {{ $perhitunganOreste->ranking_kompetensi }}</span>
                            @elseif($perhitunganOreste->ranking_kompetensi == 2)
                                <span class="badge bg-secondary fs-4">🥈 {{ $perhitunganOreste->ranking_kompetensi }}</span>
                            @elseif($perhitunganOreste->ranking_kompetensi == 3)
                                <span class="badge bg-warning fs-4">🥉 {{ $perhitunganOreste->ranking_kompetensi }}</span>
                            @else
                                <span class="badge bg-primary fs-4">{{ $perhitunganOreste->ranking_kompetensi }}</span>
                            @endif
                        </h3>
                        <p class="text-muted mb-0">Ranking Kompetensi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Penilaian -->
<div class="row mb-4">
    <!-- Penilaian Kinerja -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Detail Penilaian Kinerja</h5>
            </div>
            <div class="card-body">
                @if($penilaianKinerja->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Sub Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penilaianKinerja as $penilaian)
                                    <tr>
                                        <td>{{ $penilaian->kriteria->nama }}</td>
                                        <td>{{ $penilaian->subKriteria->nama ?? '-' }}</td>
                                        <td>{{ number_format($penilaian->nilai, 2) }}</td>
                                        <td>{{ $penilaian->kriteria->bobot }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>Rata-rata Nilai Kinerja:</strong> {{ number_format($stats['nilai_rata_kinerja'], 2) }}
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada data penilaian kinerja</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Penilaian Kompetensi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Detail Penilaian Kompetensi</h5>
            </div>
            <div class="card-body">
                @if($penilaianKompetensi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Sub Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penilaianKompetensi as $penilaian)
                                    <tr>
                                        <td>{{ $penilaian->kriteria->nama }}</td>
                                        <td>{{ $penilaian->subKriteria->nama ?? '-' }}</td>
                                        <td>{{ number_format($penilaian->nilai, 2) }}</td>
                                        <td>{{ $penilaian->kriteria->bobot }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>Rata-rata Nilai Kompetensi:</strong> {{ number_format($stats['nilai_rata_kompetensi'], 2) }}
                    </div>
                @else
                    <p class="text-muted text-center">Tidak ada data penilaian kompetensi</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Informasi Mutasi -->
@if($mutasiRequest)
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Informasi Permohonan Mutasi</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Lokasi Tujuan</th>
                        <td>: {{ $mutasiRequest->lokasi_tujuan }}</td>
                    </tr>
                    <tr>
                        <th>Alasan</th>
                        <td>: {{ $mutasiRequest->alasan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Tanggal Pengajuan</th>
                        <td>: {{ \Carbon\Carbon::parse($mutasiRequest->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            <span class="badge bg-{{
                                $mutasiRequest->status == 'diterima' ? 'success' :
                                ($mutasiRequest->status == 'ditolak' ? 'danger' : 'secondary')
                            }}">
                                {{ ucfirst($mutasiRequest->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Hasil Akhir -->
@if($hasilAkhir)
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Hasil Akhir</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h3 class="text-success">{{ number_format($hasilAkhir->nilai_akhir, 3) }}</h3>
                <p class="text-muted mb-0">Nilai Akhir</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-primary">
                    @if($hasilAkhir->ranking_akhir == 1)
                        <span class="badge bg-warning fs-4">🥇 {{ $hasilAkhir->ranking_akhir }}</span>
                    @elseif($hasilAkhir->ranking_akhir == 2)
                        <span class="badge bg-secondary fs-4">🥈 {{ $hasilAkhir->ranking_akhir }}</span>
                    @elseif($hasilAkhir->ranking_akhir == 3)
                        <span class="badge bg-warning fs-4">🥉 {{ $hasilAkhir->ranking_akhir }}</span>
                    @else
                        <span class="badge bg-primary fs-4">{{ $hasilAkhir->ranking_akhir }}</span>
                    @endif
                </h3>
                <p class="text-muted mb-0">Ranking Akhir</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-info">{{ $hasilAkhir->lokasi_mutasi ?? '-' }}</h3>
                <p class="text-muted mb-0">Lokasi Mutasi</p>
            </div>
            <div class="col-md-3">
                <span class="badge bg-{{
                    $hasilAkhir->status == 'diterima' ? 'success' :
                    ($hasilAkhir->status == 'ditolak' ? 'danger' : 'secondary')
                }} fs-4">
                    {{ ucfirst($hasilAkhir->status) }}
                </span>
                <p class="text-muted mb-0">Status</p>
            </div>
        </div>
    </div>
</div>
@endif

@endsection 