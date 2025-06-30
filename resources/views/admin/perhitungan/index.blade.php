@extends('layouts.app')

@section('title', 'Perhitungan Oreste')

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
            <a class="nav-link active" href="{{ route('admin.perhitungan.index') }}">
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
    <h4><i class="fas fa-calculator me-2"></i>Perhitungan Oreste</h4>
    <div>
        <button class="btn btn-info me-2" onclick="exportData()">
            <i class="fas fa-download me-2"></i>Export
        </button>
        <button class="btn btn-warning me-2" onclick="resetPerhitungan()">
            <i class="fas fa-undo me-2"></i>Reset
        </button>
        <button class="btn btn-success" onclick="prosesPerhitungan()">
            <i class="fas fa-play me-2"></i>Proses Perhitungan
        </button>
    </div>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Total Pegawai</h5>
                <h3>{{ $stats['total_pegawai'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <h5>Eligible Mutasi</h5>
                <h3>{{ $stats['eligible_mutasi'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-calculator fa-3x mb-3"></i>
                <h5>Sudah Dihitung</h5>
                <h3>{{ $stats['sudah_dihitung'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-3x mb-3"></i>
                <h5>Tahun Aktif</h5>
                <h3>{{ $stats['tahun_aktif'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Filter Data</h5>
            </div>
            <div class="col-md-6">
                <select class="form-control" id="tahunFilter" onchange="filterByTahun()">
                    @foreach($tahunList as $tahunOption)
                        <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                            {{ $tahunOption }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Hasil Perhitungan Kinerja -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Hasil Perhitungan Oreste - Kinerja</h5>
    </div>
    <div class="card-body">
        @if($perhitunganKinerja->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Pegawai</th>
                            <th>NIP</th>
                            <th>Nilai Kinerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perhitunganKinerja as $perhitungan)
                            <tr>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $perhitungan->ranking_kinerja }}</span>
                                </td>
                                <td>{{ $perhitungan->user->name }}</td>
                                <td>{{ $perhitungan->user->nip }}</td>
                                <td>{{ number_format($perhitungan->nilai_kinerja, 3) }}</td>
                                <td>
                                    <a href="{{ route('admin.perhitungan.show', $perhitungan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <h5 class="text-muted">Belum ada data perhitungan kinerja</h5>
            </div>
        @endif
    </div>
</div>

<!-- Hasil Perhitungan Kompetensi -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Hasil Perhitungan Oreste - Kompetensi</h5>
    </div>
    <div class="card-body">
        @if($perhitunganKompetensi->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Pegawai</th>
                            <th>NIP</th>
                            <th>Nilai Kompetensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perhitunganKompetensi as $perhitungan)
                            <tr>
                                <td>
                                    <span class="badge bg-success fs-6">{{ $perhitungan->ranking_kompetensi }}</span>
                                </td>
                                <td>{{ $perhitungan->user->name }}</td>
                                <td>{{ $perhitungan->user->nip }}</td>
                                <td>{{ number_format($perhitungan->nilai_kompetensi, 3) }}</td>
                                <td>
                                    <a href="{{ route('admin.perhitungan.show', $perhitungan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <h5 class="text-muted">Belum ada data perhitungan kompetensi</h5>
            </div>
        @endif
    </div>
</div>

<!-- Form untuk aksi -->
<form id="prosesForm" action="{{ route('admin.perhitungan.proses') }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="resetForm" action="{{ route('admin.perhitungan.reset') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

<script>
function prosesPerhitungan() {
    if (confirm('Apakah Anda yakin ingin memproses perhitungan Oreste? Proses ini akan menghitung ulang semua data.')) {
        document.getElementById('prosesForm').submit();
    }
}

function resetPerhitungan() {
    if (confirm('Apakah Anda yakin ingin mereset data perhitungan tahun {{ $tahun }}? Data akan dihapus permanen.')) {
        document.getElementById('resetForm').submit();
    }
}

// function exportData() {
//     const tahun = document.getElementById('tahunFilter').value;
//     window.open(`{{ route('admin.perhitungan.export') }}?tahun=${tahun}`, '_blank');
// }

function exportData() {
    const tahun = document.getElementById('tahunFilter').value;

    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
    btn.disabled = true;

    const exportWindow = window.open(`{{ route('admin.perhitungan.export') }}?tahun=${tahun}`, '_blank');

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 2000);
}

function filterByTahun() {
    const tahun = document.getElementById('tahunFilter').value;
    window.location.href = `{{ route('admin.perhitungan.index') }}?tahun=${tahun}`;
}
</script>
@endsection
