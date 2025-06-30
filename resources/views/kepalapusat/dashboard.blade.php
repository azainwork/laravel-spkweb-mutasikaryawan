@extends('layouts.app')

@section('title', 'Dashboard Kepala Pusat')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.dashboard') ? 'active' : '' }}" href="{{ route('kepalapusat.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.mutasi.*') ? 'active' : '' }}" href="{{ route('kepalapusat.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.pegawai.*') ? 'active' : '' }}" href="{{ route('kepalapusat.pegawai.index') }}">
                <i class="fas fa-users"></i>
                Data Pegawai
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.mutasi.*') ? 'active' : '' }}" href="{{ route('kepalapusat.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.penilaian.*') ? 'active' : '' }}" href="{{ route('kepalapusat.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('kepalapusat.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.laporan.*') ? 'active' : '' }}" href="{{ route('kepalapusat.laporan.index') }}">
                <i class="fas fa-file-pdf"></i>
                Laporan
            </a>
        </li> --}}
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard Kepala Pusat</h4>
    <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Total Pegawai</h5>
                <h3>{{ \App\Models\User::where('role_id', 2)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-3x mb-3"></i>
                <h5>Permohonan Mutasi</h5>
                <h3>{{ \App\Models\MutasiRequest::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h5>Menunggu Persetujuan</h5>
                <h3>{{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-3x mb-3"></i>
                <h5>Hasil Perangkingan</h5>
                <h3>{{ \App\Models\HasilAkhir::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Mutasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-success">{{ \App\Models\HasilAkhir::where('lokasi_mutasi', 'Terdekat')->count() }}</h4>
                            <p class="text-muted">Mutasi Terdekat</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-warning">{{ \App\Models\HasilAkhir::where('lokasi_mutasi', 'Sesuai Kebijakan')->count() }}</h4>
                            <p class="text-muted">Sesuai Kebijakan</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h4 class="text-danger">{{ \App\Models\HasilAkhir::where('lokasi_mutasi', 'Luar Kota')->count() }}</h4>
                            <p class="text-muted">Luar Kota</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Status Permohonan:</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Diterima</span>
                            <span class="badge bg-success">{{ \App\Models\MutasiRequest::where('status', 'diterima')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ditolak</span>
                            <span class="badge bg-danger">{{ \App\Models\MutasiRequest::where('status', 'ditolak')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Menunggu</span>
                            <span class="badge bg-warning">{{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Kriteria Pendidikan:</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>SMA</span>
                            <span class="badge bg-info">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'SMA')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>D3</span>
                            <span class="badge bg-info">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'D3')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>S1</span>
                            <span class="badge bg-primary">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S1')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>S2</span>
                            <span class="badge bg-success">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S2')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>S3</span>
                            <span class="badge bg-info">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S3')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifikasi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Permohonan Baru</h6>
                            <small class="text-muted">Ada {{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }} permohonan menunggu</small>
                        </div>
                        <span class="badge bg-warning rounded-pill">{{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Perhitungan Oreste</h6>
                            <small class="text-muted">Status perhitungan</small>
                        </div>
                        <span class="badge bg-{{ \App\Models\PerhitunganOreste::count() > 0 ? 'success' : 'secondary' }} rounded-pill">
                            {{ \App\Models\PerhitunganOreste::count() > 0 ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Hasil Perankingan</h6>
                            <small class="text-muted">Total hasil perankingan</small>
                        </div>
                        <span class="badge bg-info rounded-pill">{{ \App\Models\HasilAkhir::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i>Top 5 Ranking Mutasi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Nilai Akhir</th>
                        <th>Lokasi Mutasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\HasilAkhir::with('user')->orderBy('ranking_akhir')->limit(5)->get() as $hasil)
                        <tr>
                            <td>
                                @if($hasil->ranking_akhir == 1)
                                    <span class="badge bg-primary fs-6">🥇 {{ $hasil->ranking_akhir }}</span>
                                @elseif($hasil->ranking_akhir == 2)
                                    <span class="badge bg-secondary fs-6">🥈 {{ $hasil->ranking_akhir }}</span>
                                @elseif($hasil->ranking_akhir == 3)
                                    <span class="badge bg-warning fs-6">🥉 {{ $hasil->ranking_akhir }}</span>
                                @else
                                    <span class="badge bg-primary-subtle fs-6">{{ $hasil->ranking_akhir }}</span>
                                @endif
                            </td>
                            <td>{{ $hasil->user->name }}</td>
                            <td>{{ $hasil->user->nip }}</td>
                            <td>{{ $hasil->user->jabatan }}</td>
                            <td>{{ number_format($hasil->nilai_akhir, 3) }}</td>
                            <td>
                                @if($hasil->ranking_akhir == 1)
                                    <span class="badge bg-success">Terdekat</span>
                                @elseif($hasil->ranking_akhir == \App\Models\HasilAkhir::count())
                                    <span class="badge bg-danger">Luar Kota</span>
                                @else
                                    <span class="badge bg-warning">Sesuai Kebijakan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada hasil mutasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
