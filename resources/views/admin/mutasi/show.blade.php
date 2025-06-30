@extends('layouts.app')

@section('title', 'Detail Permohonan Mutasi')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
                <i class="fas fa-users"></i> Data Pegawai
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.mutasi.index') }}">
                <i class="fas fa-file-alt"></i> Permohonan Mutasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.kriteria.index') }}">
                <i class="fas fa-cogs"></i> Data Kriteria
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i> Data Penilaian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.perhitungan.index') }}">
                <i class="fas fa-calculator"></i> Perhitungan Oreste
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i> Hasil Akhir
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Permohonan Mutasi</h4>
    <div>
        <a href="{{ route('admin.mutasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama:</strong></td>
                        <td>{{ $mutasiRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>{{ $mutasiRequest->user->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan:</strong></td>
                        <td>{{ $mutasiRequest->user->jabatan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan:</strong></td>
                        <td>{{ $mutasiRequest->user->pendidikan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Masa Kerja:</strong></td>
                        <td>{{ $mutasiRequest->user->masa_kerja }} bulan</td>
                    </tr>
                    <tr>
                        <td><strong>Usia:</strong></td>
                        <td>{{ $mutasiRequest->user->usia }} tahun</td>
                    </tr>
                    @if($mutasiRequest->user->profile)
                    <tr>
                        <td><strong>No. HP:</strong></td>
                        <td>{{ $mutasiRequest->user->profile->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $mutasiRequest->user->profile->alamat ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Permohonan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Tanggal Pengajuan:</strong></td>
                        <td>{{ $mutasiRequest->tanggal_pengajuan ? \Carbon\Carbon::parse($mutasiRequest->tanggal_pengajuan)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan Terakhir:</strong></td>
                        <td>{{ $mutasiRequest->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi Tujuan:</strong></td>
                        <td>{{ $mutasiRequest->lokasi_tujuan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->status == 'menunggu' ? 'warning' :
                                ($mutasiRequest->status == 'diproses' ? 'info' :
                                ($mutasiRequest->status == 'diterima' ? 'success' :
                                ($mutasiRequest->status == 'ditolak' ? 'danger' : 'secondary')))
                            }} fs-6">
                                {{ ucfirst($mutasiRequest->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Keputusan:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->keputusan_akhir == 'diterima' ? 'success' :
                                ($mutasiRequest->keputusan_akhir == 'ditolak' ? 'danger' : 'secondary')
                            }} fs-6">
                                {{ ucfirst($mutasiRequest->keputusan_akhir) }}
                            </span>
                        </td>
                    </tr>
                    @if($mutasiRequest->tanggal_keputusan)
                    <tr>
                        <td><strong>Tanggal Keputusan:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($mutasiRequest->tanggal_keputusan)->format('d F Y') }}</td>
                    </tr>
                    @endif
                    @if($mutasiRequest->keterangan)
                    <tr>
                        <td><strong>Keterangan:</strong></td>
                        <td>{{ $mutasiRequest->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if($hasilAkhir)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Hasil Akhir ORESTE</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-center">
                    <h4 class="text-primary">{{ number_format($hasilAkhir->nilai_akhir, 2) }}</h4>
                    <p class="text-muted mb-0">Nilai Akhir</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h4 class="text-success">{{ $hasilAkhir->ranking_akhir }}</h4>
                    <p class="text-muted mb-0">Ranking</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <h4 class="text-info">{{ $hasilAkhir->lokasi_mutasi ?? '-' }}</h4>
                    <p class="text-muted mb-0">Lokasi Mutasi</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <span class="badge bg-{{
                        $hasilAkhir->status == 'diterima' ? 'success' :
                        ($hasilAkhir->status == 'ditolak' ? 'danger' : 'secondary')
                    }} fs-6">
                        {{ ucfirst($hasilAkhir->status) }}
                    </span>
                    <p class="text-muted mb-0">Status</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
