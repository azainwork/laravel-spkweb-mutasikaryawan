@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

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
    <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard Pegawai</h4>
    <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-user fa-3x mb-3"></i>
                <h5>Profil Saya</h5>
                <h3>{{ auth()->user()->jabatan }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-3x mb-3"></i>
                <h5>Permohonan Mutasi</h5>
                <h3>{{ \App\Models\MutasiRequest::where('user_id', auth()->id())->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                <h5>Penilaian</h5>
                <h3>{{ \App\Models\Penilaian::where('user_id', auth()->id())->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-3x mb-3"></i>
                <h5>Hasil Mutasi</h5>
                <h3>{{ \App\Models\HasilAkhir::where('user_id', auth()->id())->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIP:</strong></td>
                                <td>{{ auth()->user()->nip }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jabatan:</strong></td>
                                <td>{{ auth()->user()->jabatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pendidikan:</strong></td>
                                <td>{{ auth()->user()->pendidikan }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Masa Kerja:</strong></td>
                                <td>{{ auth()->user()->masa_kerja }} bulan</td>
                            </tr>
                            @php
                                $mutasi = \App\Models\MutasiRequest::where('user_id', auth()->id())->latest()->first();
                                $hasil = \App\Models\HasilAkhir::where('user_id', auth()->id())->latest()->first();
                            @endphp
                            <tr>
                                <td><strong>Status Mutasi:</strong></td>
                                <td>
                                    @if($mutasi && $hasil)
                                        <span class="badge bg-{{ $hasil->status == 'diterima' ? 'success' : ($hasil->status == 'ditolak' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($hasil->status) }}
                                        </span>
                                    @elseif($mutasi)
                                        <span class="badge bg-warning">Menunggu</span>
                                    @else
                                        <span class="badge bg-secondary">Belum ada</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi Mutasi:</strong></td>
                                <td>
                                    @if($mutasi && $hasil)
                                        <span class="badge bg-{{ $hasil->lokasi_mutasi == 'Terdekat' ? 'success' : ($hasil->lokasi_mutasi == 'Luar Kota' ? 'danger' : 'warning') }}">
                                            {{ $hasil->lokasi_mutasi }}
                                        </span>
                                    @elseif($mutasi)
                                        <span class="badge bg-warning">-</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ranking:</strong></td>
                                <td>
                                    @if($hasil)
                                        <span class="badge bg-primary">{{ $hasil->ranking_akhir }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifikasi</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Status Mutasi</h6>
                            <small class="text-muted">Permohonan terbaru</small>
                        </div>
                        @php
                            $mutasi = \App\Models\MutasiRequest::where('user_id', auth()->id())->latest()->first();
                        @endphp
                        <span class="badge bg-{{ $mutasi && $mutasi->status == 'menunggu' ? 'warning' : ($mutasi && $mutasi->status == 'diterima' ? 'success' : 'secondary') }} rounded-pill">
                            {{ $mutasi ? ucfirst($mutasi->status) : 'Belum ada' }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Penilaian</h6>
                            <small class="text-muted">Tahun {{ date('Y') }}</small>
                        </div>
                        <span class="badge bg-{{ \App\Models\Penilaian::where('user_id', auth()->id())->where('tahun', date('Y'))->count() > 0 ? 'success' : 'warning' }} rounded-pill">
                            {{ \App\Models\Penilaian::where('user_id', auth()->id())->where('tahun', date('Y'))->count() > 0 ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Eligibilitas</h6>
                            <small class="text-muted">Masa kerja {{ auth()->user()->masa_kerja }} bulan</small>
                        </div>
                        <span class="badge bg-{{ auth()->user()->masa_kerja >= 60 ? 'success' : 'danger' }} rounded-pill">
                            {{ auth()->user()->masa_kerja >= 60 ? 'Eligible' : 'Tidak Eligible' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Permohonan Mutasi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Tanggapan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\MutasiRequest::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(5)->get() as $mutasi)
                        <tr>
                            <td>{{ $mutasi->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $mutasi->status == 'diterima' ? 'success' : ($mutasi->status == 'ditolak' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($mutasi->status) }}
                                </span>
                            </td>
                            <td>{{ $mutasi->tanggapan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada permohonan mutasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
