@extends('layouts.app')

@section('title', 'Permohonan Mutasi')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.dashboard') ? 'active' : '' }}" href="{{ route('kepalapusat.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.mutasi.*') ? 'active' : '' }}" href="{{ route('kepalapusat.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('kepalapusat.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li> --}}
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-file-alt me-2"></i>Permohonan Mutasi</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('kepalapusat.mutasi.export-excel-pusat', request()->query()) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h5>Total</h5>
                <h3>{{ $totalPermohonan }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h5>Menunggu</h5>
                <h3>{{ $menunggu }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-cogs fa-2x mb-2"></i>
                <h5>Diproses</h5>
                <h3>{{ $diproses }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h5>Diterima</h5>
                <h3>{{ $diterima }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-times-circle fa-2x mb-2"></i>
                <h5>Ditolak</h5>
                <h3>{{ $ditolak }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label for="status_filter" class="form-label">Filter Status</label>
                <select class="form-select" id="status_filter">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diproses">Diproses</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Cari Pegawai</label>
                <input type="text" class="form-control" id="search" placeholder="Nama atau NIP...">
            </div>
            <div class="col-md-3">
                <label for="date_filter" class="form-label">Filter Tanggal</label>
                <input type="date" class="form-control" id="date_filter">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" onclick="filterData()">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Permohonan Mutasi -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Permohonan Mutasi</h5>
    </div>
    <div class="card-body">
        @if($mutasiRequests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="mutasiTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pegawai</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Pendidikan</th>
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
                            <td>
                                <div>
                                    <strong>{{ $mutasi->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $mutasi->user->nip }} | {{ $mutasi->user->jabatan }}</small>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($mutasi->tanggal_pengajuan)->format('d/m/Y') }}</td>
                            <td>{{ $mutasi->pendidikan_terakhir }}</td>
                            <td>{{ $mutasi->lokasi_tujuan }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $mutasi->status == 'menunggu' ? 'warning' :
                                    ($mutasi->status == 'diproses' ? 'info' :
                                    ($mutasi->status == 'diterima' ? 'success' :
                                    ($mutasi->status == 'ditolak' ? 'danger' : 'secondary')))
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
                                    <a href="{{ route('kepalapusat.mutasi.show', $mutasi->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- <a href="{{ route('kepalapusat.mutasi.edit', $mutasi->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a> --}}
                                    {{-- @if($mutasi->status == 'diproses')
                                        <form action="{{ route('kepalapusat.mutasi.approve', $mutasi->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Setujui permohonan ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('kepalapusat.mutasi.reject', $mutasi->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" title="Tolak" onclick="return confirm('Tolak permohonan ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif --}}
                                    @php
                                        $oreste = \App\Models\PerhitunganOreste::where('user_id', $mutasi->user_id)
                                            ->where('tahun', date('Y'))
                                            ->first();
                                        $hasRanking = $oreste && ($oreste->ranking_kinerja !== null || $oreste->ranking_kompetensi !== null);
                                    @endphp

                                    @if($mutasi->status == 'diproses')
                                        @if($hasRanking)
                                            <form action="{{ route('kepalapusat.mutasi.approve', $mutasi->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Setujui permohonan ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('kepalapusat.mutasi.reject', $mutasi->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak" onclick="return confirm('Tolak permohonan ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-transparent text-dark">Belum bisa diproses (ranking belum tersedia)</span>
                                        @endif
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
                <p class="text-muted">Permohonan mutasi akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>

<script>
function filterData() {
    const statusFilter = document.getElementById('status_filter').value;
    const searchFilter = document.getElementById('search').value.toLowerCase();
    const dateFilter = document.getElementById('date_filter').value;

    const table = document.getElementById('mutasiTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const status = row.cells[5].textContent.toLowerCase();
        const pegawai = row.cells[1].textContent.toLowerCase();
        const tanggal = row.cells[2].textContent;

        let showRow = true;

        if (statusFilter && !status.includes(statusFilter.toLowerCase())) {
            showRow = false;
        }

        if (searchFilter && !pegawai.includes(searchFilter)) {
            showRow = false;
        }

        if (dateFilter && tanggal !== dateFilter) {
            showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
    }
}
</script>
@endsection
