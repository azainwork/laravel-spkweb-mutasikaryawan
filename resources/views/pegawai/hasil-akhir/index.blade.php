@extends('layouts.app')

@section('title', 'Hasil Akhir')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.profil.*') ? 'active' : '' }}" href="{{ route('pegawai.profil.index') }}">
                <i class="fas fa-user"></i>
                Profil Saya
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.mutasi.*') ? 'active' : '' }}" href="{{ route('pegawai.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.penilaian.*') ? 'active' : '' }}" href="{{ route('pegawai.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('pegawai.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-trophy me-2"></i>Hasil Akhir</h4>
    <div class="d-flex align-items-center">
        <span class="text-muted me-3">Pegawai: {{ auth()->user()->name }}</span>
    </div>
</div>

@if($hasilAkhirs->count() > 0)
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h5>Total Hasil</h5>
                    <h3>{{ $totalHasil }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x mb-2"></i>
                    <h5>Nilai Tertinggi</h5>
                    <h3>{{ number_format($nilaiTertinggi, 3) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-medal fa-2x mb-2"></i>
                    <h5>Ranking Terbaik</h5>
                    <h3>{{ $rankingTerbaik ?? '-' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h5>Diterima</h5>
                    <h3>{{ $statusDiterima }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Hasil Akhir -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Hasil Akhir</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tahun</th>
                            <th>Nilai Akhir</th>
                            <th>Ranking</th>
                            <th>Lokasi Mutasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilAkhirs as $index => $hasil)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $hasil->tahun }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $hasil->nilai_akhir >= 80 ? 'success' :
                                    ($hasil->nilai_akhir >= 70 ? 'warning' : 'danger')
                                }} fs-6">
                                    {{ number_format($hasil->nilai_akhir, 3) }}
                                </span>
                            </td>
                            <td>
                                @if($hasil->ranking_akhir)
                                    <span class="badge bg-primary fs-6">#{{ $hasil->ranking_akhir }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($hasil->lokasi_mutasi)
                                    <span class="badge bg-{{
                                        $hasil->lokasi_mutasi == 'Terdekat' ? 'success' :
                                        ($hasil->lokasi_mutasi == 'Luar Kota' ? 'danger' : 'warning')
                                    }}">
                                        {{ $hasil->lokasi_mutasi }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $hasil->status == 'diterima' ? 'success' :
                                    ($hasil->status == 'ditolak' ? 'danger' :
                                    ($hasil->status == 'selesai' ? 'info' : 'warning'))
                                }}">
                                    {{ ucfirst($hasil->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pegawai.hasil-akhir.show', $hasil->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik Perbandingan -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Perkembangan Nilai</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tahun</th>
                                    <th>Nilai</th>
                                    <th>Ranking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilAkhirs->sortBy('tahun') as $hasil)
                                <tr>
                                    <td>{{ $hasil->tahun }}</td>
                                    <td>{{ number_format($hasil->nilai_akhir, 3) }}</td>
                                    <td>{{ $hasil->ranking_akhir ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Mutasi</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        @php
                            $statusCounts = $hasilAkhirs->groupBy('status')->map->count();
                        @endphp
                        @foreach($statusCounts as $status => $count)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{
                                $status == 'diterima' ? 'success' :
                                ($status == 'ditolak' ? 'danger' :
                                ($status == 'selesai' ? 'info' : 'warning'))
                            }}">
                                {{ ucfirst($status) }}
                            </span>
                            <span class="fw-bold">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada hasil akhir</h5>
            <p class="text-muted">Hasil akhir akan muncul setelah admin melakukan perhitungan ORESTE</p>
        </div>
    </div>
@endif
@endsection
