@extends('layouts.app')

@section('title', 'Data Penilaian')

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
    <h4><i class="fas fa-chart-bar me-2"></i>Data Penilaian</h4>
    <div class="d-flex align-items-center">
        <span class="text-muted me-3">Pegawai: {{ auth()->user()->name }}</span>
        <a href="{{ route('pegawai.penilaian.export-pdf') }}" class="btn btn-danger ms-2" target="_blank">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

@if($penilaiansByYear->count() > 0)
    @foreach($penilaiansByYear as $tahun => $penilaians)
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar me-2"></i>Penilaian Tahun {{ $tahun }}
            </h5>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-2">Rata-rata: {{ number_format($averageByYear[$tahun], 2) }}</span>
                <span class="badge bg-info">{{ $penilaians->count() }} Kriteria</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Nilai</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penilaians as $index => $penilaian)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $penilaian->kriteria->nama }}</strong>
                                <br>
                                <small class="text-muted">Bobot: {{ $penilaian->kriteria->bobot }}%</small>
                            </td>
                            <td>
                                {{ $penilaian->subKriteria->nama }}
                                <br>
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $penilaian->nilai >= 80 ? 'success' :
                                    ($penilaian->nilai >= 70 ? 'warning' : 'danger')
                                }}">
                                    {{ $penilaian->nilai }}
                                </span>
                            </td>
                            <td>
                                @if($penilaian->catatan)
                                    <span class="text-muted">{{ Str::limit($penilaian->catatan, 50) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pegawai.penilaian.show', $penilaian->id) }}" class="btn btn-sm btn-info">
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
    @endforeach

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h5>Total Tahun Penilaian</h5>
                    <h3>{{ $penilaiansByYear->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-star fa-2x mb-2"></i>
                    <h5>Rata-rata Tertinggi</h5>
                    <h3>{{ number_format(collect($averageByYear)->max(), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h5>Total Kriteria Dinilai</h5>
                    <h3>{{ $penilaiansByYear->flatten(1)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada data penilaian</h5>
            <p class="text-muted">Data penilaian akan muncul setelah admin melakukan penilaian</p>
        </div>
    </div>
@endif
@endsection
