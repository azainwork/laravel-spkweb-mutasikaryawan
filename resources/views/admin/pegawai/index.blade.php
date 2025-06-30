@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.pegawai.index') }}">
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
    <h4><i class="fas fa-users me-2"></i>Data Pegawai</h4>
    <a  href="{{ route('admin.pegawai.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Pegawai
    </a>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Daftar Pegawai</h5>
            </div>
            <div class="col-md-6">
                {{-- <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari pegawai...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div> --}}
                <form action="{{ route('admin.pegawai.index') }}" method="GET" class="d-flex">
                    <input type="text"
                           class="form-control me-2"
                           name="search"
                           placeholder="Cari nama, NIP, jabatan, atau pendidikan..."
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-danger ms-2" title="Hapus pencarian">
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
                <span class="badge bg-primary ms-2">{{ $pegawais->count() }} hasil</span>
            </div>
        @endif
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $pegawai)
                        <tr>
                            <td>{{ $pegawai->nip }}</td>
                            <td>{{ $pegawai->name }}</td>
                            <td>{{ $pegawai->jabatan }}</td>
                            <td>{{ $pegawai->pendidikan }}</td>
                            <td>{{ floor($pegawai->masa_kerja/12) }} tahun {{ $pegawai->masa_kerja%12 }} bulan</td>
                            <td>{{ $pegawai->usia }} tahun</td>
                            <td>
                                <a href="{{ route('admin.pegawai.show', $pegawai->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a  href="{{ route('admin.pegawai.edit', $pegawai->id) }}"  class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form id="delete-form-{{ $pegawai->id }}" action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $pegawai->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                @if(request('search'))
                                    Tidak ada data pegawai yang sesuai dengan pencarian "{{ request('search') }}"
                                @else
                                    Tidak ada data pegawai.
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
        const searchForm = document.querySelector('form[action*="pegawai"]');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                searchForm.submit();
            }, 500); // Delay 500ms setelah user berhenti mengetik
        });
    });

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pegawai ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
