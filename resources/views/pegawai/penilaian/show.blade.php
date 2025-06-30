@extends('layouts.app')

@section('title', 'Detail Penilaian')

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
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Penilaian</h4>
    <a href="{{ route('pegawai.penilaian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama:</strong></td>
                        <td>{{ $penilaian->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>{{ $penilaian->user->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan:</strong></td>
                        <td>{{ $penilaian->user->jabatan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tahun Penilaian:</strong></td>
                        <td>{{ $penilaian->tahun }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Hasil Penilaian</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Kriteria:</strong></td>
                        <td>{{ $penilaian->kriteria->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Bobot Kriteria:</strong></td>
                        <td>{{ $penilaian->kriteria->bobot }}%</td>
                    </tr>
                    <tr>
                        <td><strong>Sub Kriteria:</strong></td>
                        <td>{{ $penilaian->subKriteria->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nilai Akhir:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $penilaian->nilai >= 80 ? 'success' :
                                ($penilaian->nilai >= 70 ? 'warning' : 'danger')
                            }} fs-6">
                                {{ $penilaian->nilai }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Catatan Penilaian</h5>
    </div>
    <div class="card-body">
        @if($penilaian->catatan)
            <p class="mb-0">{{ $penilaian->catatan }}</p>
        @else
            <p class="text-muted mb-0">Tidak ada catatan</p>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Tanggal Input:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($penilaian->created_at)->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terakhir Update:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($penilaian->updated_at)->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Deskripsi Kriteria:</strong></td>
                        <td>{{ $penilaian->kriteria->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi Sub Kriteria:</strong></td>
                        <td>{{ $penilaian->subKriteria->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
