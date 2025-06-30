@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.pegawai.index') }}">
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
            <a class="nav-link active" href="{{ route('admin.penilaian.index') }}">
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
    <h4><i class="fas fa-eye me-2"></i>Detail Penilaian</h4>
    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Penilaian</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th>Pegawai</th>
                <td>{{ $penilaian->user->name }} ({{ $penilaian->user->nip }})</td>
            </tr>
            <tr>
                <th>Kriteria</th>
                <td>{{ $penilaian->kriteria->nama }}</td>
            </tr>
            <tr>
                <th>Sub Kriteria</th>
                <td>{{ $penilaian->subKriteria->nama }}</td>
            </tr>
            <tr>
                <th>Nilai</th>
                <td>{{ $penilaian->nilai }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>
                    @if($penilaian->nilai > 90)
                        <span class="badge bg-success">Sangat Baik</span>
                    @elseif($penilaian->nilai > 75)
                        <span class="badge bg-primary">Baik</span>
                    @elseif($penilaian->nilai > 60)
                        <span class="badge bg-warning">Cukup</span>
                    @elseif($penilaian->nilai > 50)
                        <span class="badge bg-danger">Kurang</span>
                    @else
                        <span class="badge bg-dark">Buruk</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $penilaian->tahun }}</td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>{{ $penilaian->catatan ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
