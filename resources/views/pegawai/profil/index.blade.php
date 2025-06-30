@extends('layouts.app')

@section('title', 'Profil Saya')

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
    <h4><i class="fas fa-user me-2"></i>Profil Saya</h4>
    <a href="{{ route('pegawai.profil.edit') }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i>Edit Profil
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($user->profile && $user->profile->foto)
                        <img src="{{ asset('storage/' . $user->profile->foto) }}" alt="Foto Profil" class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-white"></i>
                        </div>
                    @endif
                </div>
                <h5 class="card-title">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->jabatan }}</p>
                <div class="d-grid">
                    <a href="{{ route('pegawai.profil.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama Lengkap:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIP:</strong></td>
                                <td>{{ $user->nip }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jabatan:</strong></td>
                                <td>{{ $user->jabatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pendidikan:</strong></td>
                                <td>{{ $user->pendidikan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Masa Kerja:</strong></td>
                                <td>{{ $user->masa_kerja }} bulan</td>
                            </tr>
                            <tr>
                                <td><strong>Usia:</strong></td>
                                <td>{{ $user->usia }} tahun</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($user->profile)
                                <tr>
                                    <td width="40%"><strong>Tempat Lahir:</strong></td>
                                    <td>{{ $user->profile->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td>{{ $user->profile->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->format('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td>{{ $user->profile->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Perkawinan:</strong></td>
                                    <td>{{ $user->profile->status_perkawinan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Agama:</strong></td>
                                    <td>{{ $user->profile->agama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP:</strong></td>
                                    <td>{{ $user->profile->no_hp }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $user->profile->alamat }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Data profil belum lengkap
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($user->profile)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Alamat Lengkap</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $user->profile->alamat }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
