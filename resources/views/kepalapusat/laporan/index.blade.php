@extends('layouts.app')

@section('title', 'Laporan')

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
            <a class="nav-link active" href="#">
                <i class="fas fa-file-pdf"></i>
                Laporan
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-file-pdf me-2"></i>Laporan</h4>
    <span class="text-muted">Pilih jenis laporan yang ingin dicetak</span>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h5>Laporan Data Pegawai</h5>
                <p class="text-muted">Laporan lengkap data pegawai beserta informasi mutasi</p>
                <button class="btn btn-primary" onclick="cetakLaporan('pegawai')">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                <h5>Laporan Permohonan Mutasi</h5>
                <p class="text-muted">Laporan status permohonan mutasi pegawai</p>
                <button class="btn btn-success" onclick="cetakLaporan('permohonan')">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                <h5>Laporan Penilaian</h5>
                <p class="text-muted">Laporan penilaian kinerja dan kompetensi pegawai</p>
                <button class="btn btn-warning" onclick="cetakLaporan('penilaian')">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-3x text-info mb-3"></i>
                <h5>Laporan Hasil Akhir</h5>
                <p class="text-muted">Laporan ranking final dan lokasi mutasi pegawai</p>
                <button class="btn btn-info" onclick="cetakLaporan('hasil')">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-3x text-danger mb-3"></i>
                <h5>Laporan Statistik</h5>
                <p class="text-muted">Laporan statistik mutasi dan analisis data</p>
                <button class="btn btn-danger" onclick="cetakLaporan('statistik')">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                <h5>Export Excel</h5>
                <p class="text-muted">Export semua data ke format Excel</p>
                <button class="btn btn-success" onclick="exportExcel()">
                    <i class="fas fa-download me-2"></i>Download
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Laporan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Laporan</th>
                        <th>Dicetak Oleh</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-01-15 10:30</td>
                        <td>Laporan Hasil Akhir</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2024-01-14 14:20</td>
                        <td>Laporan Permohonan Mutasi</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2024-01-13 09:15</td>
                        <td>Laporan Data Pegawai</td>
                        <td>{{ auth()->user()->name }}</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Laporan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Format Laporan:</h6>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="format" id="pdf" checked>
                    <label class="form-check-label" for="pdf">
                        PDF
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="format" id="excel">
                    <label class="form-check-label" for="excel">
                        Excel
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Filter Data:</h6>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control">
                        <option value="">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-control">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cetakLaporan(jenis) {
    const format = document.querySelector('input[name="format"]:checked').id;
    console.log(`Cetak laporan ${jenis} dalam format ${format}`);
}

function exportExcel() {
    console.log('Export semua data ke Excel');
}
</script>
@endsection
