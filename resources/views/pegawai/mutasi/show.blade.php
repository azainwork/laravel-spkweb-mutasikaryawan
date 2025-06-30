@extends('layouts.app')

@section('title', 'Detail Permohonan Mutasi')

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
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Permohonan Mutasi</h4>
    <div>
        @if($mutasiRequest->status == 'menunggu')
            <a href="{{ route('pegawai.mutasi.edit', $mutasiRequest->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
        @endif
        <a href="{{ route('pegawai.mutasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
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
                        <td><strong>Masa Kerja:</strong></td>
                        <td>{{ $mutasiRequest->user->masa_kerja }} bulan</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Permohonan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->status == 'menunggu' ? 'warning' :
                                ($mutasiRequest->status == 'diterima' ? 'success' :
                                ($mutasiRequest->status == 'ditolak' ? 'danger' :
                                ($mutasiRequest->status == 'diproses' ? 'info' : 'secondary')))
                            }}">
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
                            }}">
                                {{ ucfirst($mutasiRequest->keputusan_akhir) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengajuan:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($mutasiRequest->tanggal_pengajuan)->format('d F Y') }}</td>
                    </tr>
                    @if($mutasiRequest->tanggal_keputusan)
                    <tr>
                        <td><strong>Tanggal Keputusan:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($mutasiRequest->tanggal_keputusan)->format('d F Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Permohonan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Pendidikan Terakhir:</strong></td>
                        <td>{{ $mutasiRequest->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi Tujuan:</strong></td>
                        <td>{{ $mutasiRequest->lokasi_tujuan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Alasan Mutasi:</strong></td>
                        <td>{{ $mutasiRequest->alasan_mutasi }}</td>
                    </tr>
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
@endsection
