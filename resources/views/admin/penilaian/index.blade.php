@extends('layouts.app')

@section('title', 'Data Penilaian')

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
            <a class="nav-link active" href="{{ route('admin.penilaian.index') }}">
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
    <h4><i class="fas fa-chart-bar me-2"></i>Data Penilaian</h4>
    <div>
        <button class="btn btn-info me-2" onclick="validateCompleteness()">
            <i class="fas fa-check-circle me-2"></i>Validasi Kelengkapan
        </button>
        <a href="{{ route('admin.penilaian.bulk-create') }}" class="btn btn-success me-2">
            <i class="fas fa-upload me-2"></i>Input Massal
        </a>
        <a href="{{ route('admin.penilaian.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Penilaian
        </a>
    </div>
</div>

<!-- Statistik Penilaian -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                <h5>Total Penilaian</h5>
                <h3>{{ \App\Models\Penilaian::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-3x mb-3"></i>
                <h5>Tahun Aktif</h5>
                <h3>{{ \App\Models\Penilaian::distinct()->count('tahun') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h5>Pegawai Dinilai</h5>
                <h3>{{ \App\Models\Penilaian::distinct()->count('user_id') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-star fa-3x mb-3"></i>
                <h5>Rata-rata Nilai</h5>
                <h3>{{ number_format(\App\Models\Penilaian::avg('nilai'), 1) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-2">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control" id="pegawai-filter">
                            <option value="">Semua Pegawai</option>
                            @foreach($pegawaiEligible as $pegawai)
                                <option value="{{ $pegawai->name }}">{{ $pegawai->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="tahun-filter">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="kriteria-filter">
                            <option value="">Semua Kriteria</option>
                            @foreach(\App\Models\Kriteria::all() as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchPenilaian" placeholder="Cari pegawai...">
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
            <table class="table table-hover" id="penilaianTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Nilai</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penilaianByTahun->flatten() as $index => $penilaian)
                        <tr data-pegawai="{{ $penilaian->user->name }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $penilaian->user->name }}</td>
                            <td>{{ $penilaian->user->nip }}</td>
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
                                <a href="{{ route('admin.penilaian.show', $penilaian->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.penilaian.edit', $penilaian->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form id="delete-form-{{ $penilaian->id }}" action="{{ route('admin.penilaian.destroy', $penilaian->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $penilaian->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data penilaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Keterangan Kategori Penilaian</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Kriteria Kinerja</strong>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">Sangat Baik</span>
                        <span>91 - 100</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">Baik</span>
                        <span>76 - 90</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">Cukup</span>
                        <span>61 - 75</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">Kurang</span>
                        <span>50 - 60</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <strong>Kriteria Kompetensi: Masa Kerja (bulan)</strong>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">Sangat Lama</span>
                        <span>&ge; 120</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">Lama</span>
                        <span>60 - 119</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">Cukup</span>
                        <span>36 - 59</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">Kurang</span>
                        <span>&lt; 36</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <strong>Kriteria Kompetensi: Pendidikan</strong>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">S2</span>
                        <span>100</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">S1</span>
                        <span>90 - 99</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">D3</span>
                        <span>80 - 89</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">SMA</span>
                        <span>70 - 79</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <strong>Kriteria Kompetensi: Usia (tahun)</strong>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">Sangat Matang</span>
                        <span>50 - 65</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">Matang</span>
                        <span>40 - 49</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning me-2">Cukup</span>
                        <span>30 - 39</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger me-2">Muda</span>
                        <span>20 - 29</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Keterangan Kategori Penilaian</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">Sangat Baik</span>
                    <span>Nilai > 90</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2">Baik</span>
                    <span>Nilai 75 - 90</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning me-2">Cukup</span>
                    <span>Nilai 60 - 75</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-danger me-2">Kurang</span>
                    <span>Nilai 50 - 60</span>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<script>
function validateCompleteness() {
    fetch('{{ route("admin.penilaian.validate-completeness") }}')
        .then(response => response.json())
        .then(data => {
            let msg = '';
            data.forEach(item => {
                msg += `${item.user_name}: ${item.penilaian_count}/${item.total_kriteria} penilaian (${item.percentage}%)`;
                msg += item.is_complete ? ' ✅\n' : ' ❌\n';
            });
            alert(msg);
        })
        .catch(() => {
            alert('Gagal mengambil data validasi.');
        });
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penilaian ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

document.getElementById('searchPenilaian').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('penilaianTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let row of rows) {
        const nameCell = row.cells[1];
        if (nameCell) {
            const name = nameCell.textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
});

document.getElementById('pegawai-filter').addEventListener('change', function() {
    const nama = this.value.toLowerCase();
    const table = document.getElementById('penilaianTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let row of rows) {
        if (!row.hasAttribute('data-pegawai')) continue;
        const namaRow = row.getAttribute('data-pegawai').toLowerCase();
        row.style.display = (!nama || namaRow === nama) ? '' : 'none';
    }
});
</script>
@endsection
