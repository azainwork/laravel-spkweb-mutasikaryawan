@extends('layouts.app')

@section('title', 'Permohonan Mutasi')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
            <a class="nav-link active" href="{{ route('admin.mutasi.index') }}">
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
    <h4><i class="fas fa-file-alt me-2"></i>Permohonan Mutasi</h4>
    <div>
        <a href="{{ route('admin.mutasi.export-excel', request()->query()) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a href="{{ route('admin.mutasi.export-pdf', request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Daftar Permohonan Mutasi</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.mutasi.index') }}" method="GET" class="d-flex">
                    <input type="text"
                           class="form-control me-2"
                           name="search"
                           placeholder="Cari nama, NIP, jabatan, lokasi..."
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.mutasi.index') }}" class="btn btn-outline-danger ms-2" title="Hapus pencarian">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(request('search'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Menampilkan hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                <span class="badge bg-primary ms-2">{{ $mutasi->count() }} hasil</span>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Pendidikan</th>
                        <th>Alasan Mutasi</th>
                        <th>Lokasi Tujuan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutasi as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->nip }}</td>
                            <td>{{ $item->user->jabatan }}</td>
                            <td>{{ $item->pendidikan_terakhir }}</td>
                            <td>{{ Str::limit($item->alasan_mutasi, 50) }}</td>
                            <td>{{ $item->lokasi_tujuan }}</td>
                            <td>{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($item->status == 'menunggu')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($item->status == 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.mutasi.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                @if(request('search'))
                                    Tidak ada data yang sesuai dengan pencarian "{{ request('search') }}"
                                @else
                                    Tidak ada permohonan mutasi.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = document.querySelector('form[action*="mutasi"]');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                searchForm.submit();
            }, 500); // Delay 500ms setelah user berhenti mengetik
        });
    });

    function updateStatus(id, status) {
        const action = status === 'diterima' ? 'menerima' : 'menolak';
        if (confirm(`Apakah Anda yakin ingin ${action} permohonan ini?`)) {
            console.log('Update status:', id, status);
        }
    }
</script>
@endsection
