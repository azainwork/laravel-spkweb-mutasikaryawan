@extends('layouts.app')

@section('title', 'Permohonan Mutasi')

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
    <h4><i class="fas fa-file-alt me-2"></i>Permohonan Mutasi</h4>
    <a href="{{ route('pegawai.mutasi.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Ajukan Mutasi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Permohonan Mutasi</h5>
    </div>
    <div class="card-body">
        @if($mutasiRequests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Lokasi Tujuan</th>
                            <th>Status</th>
                            <th>Keputusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mutasiRequests as $index => $mutasi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($mutasi->tanggal_pengajuan)->format('d/m/Y') }}</td>
                            <td>{{ $mutasi->pendidikan_terakhir }}</td>
                            <td>{{ $mutasi->lokasi_tujuan }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $mutasi->status == 'menunggu' ? 'warning' :
                                    ($mutasi->status == 'diterima' ? 'success' :
                                    ($mutasi->status == 'ditolak' ? 'danger' :
                                    ($mutasi->status == 'diproses' ? 'info' : 'secondary')))
                                }}">
                                    {{ ucfirst($mutasi->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $mutasi->keputusan_akhir == 'diterima' ? 'success' :
                                    ($mutasi->keputusan_akhir == 'ditolak' ? 'danger' : 'secondary')
                                }}">
                                    {{ ucfirst($mutasi->keputusan_akhir) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pegawai.mutasi.show', $mutasi->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($mutasi->status == 'menunggu')
                                        <a href="{{ route('pegawai.mutasi.edit', $mutasi->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pegawai.mutasi.destroy', $mutasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan permohonan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada permohonan mutasi</h5>
                <p class="text-muted">Silakan ajukan permohonan mutasi baru</p>
                <a href="{{ route('pegawai.mutasi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Ajukan Mutasi
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
