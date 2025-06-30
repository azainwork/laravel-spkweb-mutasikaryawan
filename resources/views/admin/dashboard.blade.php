@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.dashboard') }}">
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
<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Total Pegawai</h4>
                        <p class="mb-0">Jumlah seluruh pegawai terdaftar</p>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <hr>
                <h3 class="text-center">{{ \App\Models\User::where('role_id', 2)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Permohonan Baru</h4>
                        <p class="mb-0">Mutasi menunggu persetujuan</p>
                    </div>
                    <i class="fas fa-file-alt fa-3x"></i>
                </div>
                <hr>
                <h3 class="text-center">{{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">Mutasi Selesai</h4>
                        <p class="mb-0">Jumlah mutasi yang disetujui</p>
                    </div>
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
                <hr>
                <h3 class="text-center">{{ \App\Models\MutasiRequest::where('status', 'diterima')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- <div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
    </div>
    <div class="card-body">
        <p>Belum ada aktivitas terbaru.</p>
    </div>
</div> --}}
@endsection
