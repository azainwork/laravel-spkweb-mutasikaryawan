@extends('layouts.app')

@section('title', 'Hasil Akhir')

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
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-trophy me-2"></i>Hasil Akhir Mutasi</h4>
    {{-- <div>
        <button class="btn btn-success me-2" onclick="exportExcel()">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </button>
        <button class="btn btn-danger" onclick="exportPDF()">
            <i class="fas fa-file-pdf me-2"></i>Cetak PDF
        </button>
    </div> --}}
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-medal fa-3x mb-3"></i>
                <h5>Ranking Tertinggi</h5>
                <h3>{{ \App\Models\HasilAkhir::orderBy('ranking_akhir')->first() ? \App\Models\HasilAkhir::orderBy('ranking_akhir')->first()->user->name : '-' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                <h5>Mutasi Terdekat</h5>
                <h3>{{ \App\Models\HasilAkhir::where('ranking_akhir', 1)->first() ? \App\Models\HasilAkhir::where('ranking_akhir', 1)->first()->user->name : '-' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-plane fa-3x mb-3"></i>
                <h5>Mutasi Luar Kota</h5>
                <h3>{{ \App\Models\HasilAkhir::orderByDesc('ranking_akhir')->first() ? \App\Models\HasilAkhir::orderByDesc('ranking_akhir')->first()->user->name : '-' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Total Peserta</h5>
                <h3>{{ \App\Models\HasilAkhir::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i>Ranking Final Mutasi Pegawai</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Nilai Akhir</th>
                        <th>Lokasi Mutasi</th>
                        <th>Status</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\HasilAkhir::with('user')->orderBy('ranking_akhir')->get() as $hasil)
                        <tr>
                            <td>
                                @if($hasil->ranking_akhir == 1)
                                    <span class="badge bg-warning fs-6">🥇 {{ $hasil->ranking_akhir }}</span>
                                @elseif($hasil->ranking_akhir == 2)
                                    <span class="badge bg-secondary fs-6">🥈 {{ $hasil->ranking_akhir }}</span>
                                @elseif($hasil->ranking_akhir == 3)
                                    <span class="badge bg-warning fs-6">🥉 {{ $hasil->ranking_akhir }}</span>
                                @else
                                    <span class="badge bg-primary fs-6">{{ $hasil->ranking_akhir }}</span>
                                @endif
                            </td>
                            <td>{{ $hasil->user->name }}</td>
                            <td>{{ $hasil->user->nip }}</td>
                            <td>{{ $hasil->user->jabatan }}</td>
                            <td>{{ number_format($hasil->nilai_akhir, 3) }}</td>
                            <td>
                                @if($hasil->ranking_akhir == 1)
                                    <span class="badge bg-success">Terdekat</span>
                                @elseif($hasil->ranking_akhir == \App\Models\HasilAkhir::count())
                                    <span class="badge bg-danger">Luar Kota</span>
                                @else
                                    <span class="badge bg-warning">Sesuai Kebijakan</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $hasil->status == 'menunggu' ? 'warning' : ($hasil->status == 'diterima' ? 'success' : 'danger') }}">
                                    {{ ucfirst($hasil->status) }}
                                </span>
                            </td>
                            {{-- <td>
                                <a href="#" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada hasil akhir. Silakan proses perhitungan Oreste terlebih dahulu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function exportExcel() {
    // Implement Excel export functionality
    console.log('Export to Excel');
}

function exportPDF() {
    // Implement PDF export functionality
    console.log('Export to PDF');
}
</script>
@endsection
