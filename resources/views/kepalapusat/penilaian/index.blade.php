@extends('layouts.app')

@section('title', 'Data Penilaian')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
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
            <a class="nav-link active" href="#">
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
    <h4><i class="fas fa-chart-bar me-2"></i>Data Penilaian</h4>
    <div>
        <button class="btn btn-success me-2">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </button>
        <button class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x mb-3"></i>
                <h5>Rata-rata Kinerja</h5>
                <h3>{{ number_format(\App\Models\Penilaian::whereHas('kriteria', fn($q) => $q->where('nama', 'Kinerja'))->avg('nilai'), 1) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                <h5>Rata-rata Kompetensi</h5>
                <h3>{{ number_format(\App\Models\Penilaian::whereHas('kriteria', fn($q) => $q->where('nama', 'Kompetensi'))->avg('nilai'), 1) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-star fa-3x mb-3"></i>
                <h5>Total Penilaian</h5>
                <h3>{{ \App\Models\Penilaian::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Pegawai Dinilai</h5>
                <h3>{{ \App\Models\Penilaian::distinct('user_id')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Daftar Penilaian Pegawai</h5>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" id="tahun-filter">
                            <option value="">Semua Tahun</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" id="kriteria-filter">
                            <option value="">Semua Kriteria</option>
                            <option value="Kinerja">Kinerja</option>
                            <option value="Kompetensi">Kompetensi</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari pegawai...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Nilai</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Penilaian::with(['user', 'kriteria', 'subKriteria'])->get() as $index => $penilaian)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $penilaian->user->name }}</td>
                            <td>{{ $penilaian->user->nip }}</td>
                            <td>{{ $penilaian->user->jabatan }}</td>
                            <td>{{ $penilaian->kriteria->nama }}</td>
                            <td>{{ $penilaian->subKriteria->nama }}</td>
                            <td>{{ $penilaian->nilai }}</td>
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
                            <td>{{ $penilaian->tahun }}</td>
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
                            <td colspan="10" class="text-center">Tidak ada data penilaian.</td>
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
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribusi Kategori Penilaian</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Sangat Baik (>90)</span>
                    <span class="badge bg-success">{{ \App\Models\Penilaian::where('nilai', '>', 90)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Baik (75-90)</span>
                    <span class="badge bg-primary">{{ \App\Models\Penilaian::whereBetween('nilai', [75, 90])->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Cukup (60-75)</span>
                    <span class="badge bg-warning">{{ \App\Models\Penilaian::whereBetween('nilai', [60, 75])->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Kurang (50-60)</span>
                    <span class="badge bg-danger">{{ \App\Models\Penilaian::whereBetween('nilai', [50, 60])->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Buruk (<50)</span>
                    <span class="badge bg-dark">{{ \App\Models\Penilaian::where('nilai', '<', 50)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Rata-rata per Kriteria</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Kinerja</span>
                    <span class="badge bg-primary">{{ number_format(\App\Models\Penilaian::whereHas('kriteria', fn($q) => $q->where('nama', 'Kinerja'))->avg('nilai'), 1) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Kompetensi</span>
                    <span class="badge bg-success">{{ number_format(\App\Models\Penilaian::whereHas('kriteria', fn($q) => $q->where('nama', 'Kompetensi'))->avg('nilai'), 1) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Orientasi Pelayanan</span>
                    <span class="badge bg-info">{{ number_format(\App\Models\Penilaian::whereHas('subKriteria', fn($q) => $q->where('nama', 'Orientasi Pelayanan'))->avg('nilai'), 1) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Integritas</span>
                    <span class="badge bg-info">{{ number_format(\App\Models\Penilaian::whereHas('subKriteria', fn($q) => $q->where('nama', 'Integritas'))->avg('nilai'), 1) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Masa Kerja</span>
                    <span class="badge bg-info">{{ number_format(\App\Models\Penilaian::whereHas('subKriteria', fn($q) => $q->where('nama', 'Masa Kerja'))->avg('nilai'), 1) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 