@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
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
            <a class="nav-link active" href="{{ route('admin.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        <li class="nav-item active">
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
    <h4><i class="fas fa-cogs me-2"></i>Detail Kriteria</h4>
    <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Kriteria</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width: 200px;">Nama Kriteria</th>
                <td>{{ $kriteria->nama }}</td>
            </tr>
            <tr>
                <th>Tipe</th>
                <td>
                    @if($kriteria->tipe == 'benefit')
                        <span class="badge bg-success">Benefit</span>
                    @else
                        <span class="badge bg-warning">Cost</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Bobot</th>
                <td><span class="badge bg-primary">{{ number_format($kriteria->bobot, 2) }}</span></td>
            </tr>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Sub Kriteria</h5>
    </div>
    <div class="card-body">
        @if($kriteria->subKriterias->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Bobot</th>
                        <th>Nilai Min</th>
                        <th>Nilai Max</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria->subKriterias as $sub)
                    <tr>
                        <td>{{ $sub->nama }}</td>
                        <td><span class="badge bg-primary">{{ $sub->bobot }}</span></td>
                        <td>{{ $sub->nilai_min ?? '-' }}</td>
                        <td>{{ $sub->nilai_max ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted">Belum ada sub kriteria.</p>
        @endif
    </div>
</div>
@endsection
