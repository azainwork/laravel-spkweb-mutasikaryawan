@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.pegawai.index') }}">
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
            <a class="nav-link" href="{{ route('admin.perhitungan.index') }}">
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
    <h4><i class="fas fa-user me-2"></i>Detail Pegawai</h4>
    <div>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Foto dan Info Dasar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($pegawai->profile && $pegawai->profile->foto)
                    <img src="{{ Storage::url($pegawai->profile->foto) }}"
                         alt="Foto {{ $pegawai->name }}"
                         class="img-fluid rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-4x text-muted"></i>
                    </div>
                @endif
                <h5 class="mb-1">{{ $pegawai->name }}</h5>
                <p class="text-muted mb-2">{{ $pegawai->jabatan }}</p>
                <span class="badge bg-primary">{{ ucfirst($pegawai->role->name) }}</span>
            </div>
        </div>

        <!-- Statistik -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary">{{ $pegawai->mutasiRequests->count() }}</h6>
                        <small class="text-muted">Permohonan</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success">{{ $pegawai->penilaians->count() }}</h6>
                        <small class="text-muted">Penilaian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Lengkap Pegawai -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Data Lengkap Pegawai</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px;">NIP</th>
                        <td>{{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $pegawai->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $pegawai->email }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $pegawai->jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>{{ $pegawai->pendidikan }}</td>
                    </tr>
                    <tr>
                        <th>Masa Kerja</th>
                        <td>{{ floor($pegawai->masa_kerja/12) }} tahun {{ $pegawai->masa_kerja%12 }} bulan</td>
                    </tr>
                    <tr>
                        <th>Usia</th>
                        <td>{{ $pegawai->usia }} tahun</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>
                            {{ $pegawai->profile->tempat_lahir ?? '-' }},
                            {{ $pegawai->profile->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->profile->tanggal_lahir)->format('d-m-Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>
                            @if($pegawai->profile && $pegawai->profile->jenis_kelamin)
                                {{ $pegawai->profile->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status Perkawinan</th>
                        <td>{{ $pegawai->profile->status_perkawinan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $pegawai->profile->agama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td>{{ $pegawai->profile->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pegawai->profile->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
