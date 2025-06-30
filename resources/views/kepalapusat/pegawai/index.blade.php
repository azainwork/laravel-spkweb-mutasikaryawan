@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">
                <i class="fas fa-users"></i>
                Data Pegawai
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-file-pdf"></i>
                Laporan
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-users me-2"></i>Data Pegawai</h4>
    <div>
        <button class="btn btn-success me-2">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </button>
        <button class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Daftar Pegawai</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari pegawai...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Pendidikan</th>
                        <th>Masa Kerja</th>
                        <th>Usia</th>
                        <th>Status Mutasi</th>
                        <th>Ranking</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\User::where('role_id', 2)->get() as $pegawai)
                        @php 
                            $hasil = \App\Models\HasilAkhir::where('user_id', $pegawai->id)->first();
                            $mutasi = \App\Models\MutasiRequest::where('user_id', $pegawai->id)->latest()->first();
                        @endphp
                        <tr>
                            <td>{{ $pegawai->nip }}</td>
                            <td>{{ $pegawai->name }}</td>
                            <td>{{ $pegawai->jabatan }}</td>
                            <td>{{ $pegawai->pendidikan }}</td>
                            <td>{{ floor($pegawai->masa_kerja/12) }} tahun {{ $pegawai->masa_kerja%12 }} bulan</td>
                            <td>{{ $pegawai->usia }} tahun</td>
                            <td>
                                @if($mutasi)
                                    @if($mutasi->status == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($mutasi->status == 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($mutasi->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Ajukan</span>
                                @endif
                            </td>
                            <td>
                                @if($hasil)
                                    @if($hasil->ranking_akhir == 1)
                                        <span class="badge bg-warning">🥇 {{ $hasil->ranking_akhir }}</span>
                                    @elseif($hasil->ranking_akhir == 2)
                                        <span class="badge bg-secondary">🥈 {{ $hasil->ranking_akhir }}</span>
                                    @elseif($hasil->ranking_akhir == 3)
                                        <span class="badge bg-warning">🥉 {{ $hasil->ranking_akhir }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ $hasil->ranking_akhir }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Pendidikan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>S1</span>
                    <span class="badge bg-primary">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S1')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>S2</span>
                    <span class="badge bg-success">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S2')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>S3</span>
                    <span class="badge bg-info">{{ \App\Models\User::where('role_id', 2)->where('pendidikan', 'S3')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Status Mutasi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Menunggu</span>
                    <span class="badge bg-warning">{{ \App\Models\MutasiRequest::where('status', 'menunggu')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Diterima</span>
                    <span class="badge bg-success">{{ \App\Models\MutasiRequest::where('status', 'diterima')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Ditolak</span>
                    <span class="badge bg-danger">{{ \App\Models\MutasiRequest::where('status', 'ditolak')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 