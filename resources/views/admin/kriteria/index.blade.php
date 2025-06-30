@extends('layouts.app')

@section('title', 'Data Kriteria')

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
            <a class="nav-link active" href="{{ route('admin.kriteria.index') }}">
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
    <h4><i class="fas fa-cogs me-2"></i>Data Kriteria</h4>
    <div>
        <button class="btn btn-info me-2" onclick="validateWeights()">
            <i class="fas fa-check-circle me-2"></i>Validasi Bobot
        </button>
        <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Kriteria
        </a>
    </div>
</div>

<!-- Statistik Kriteria -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-cogs fa-3x mb-3"></i>
                <h5>Total Kriteria</h5>
                <h3>{{ \App\Models\Kriteria::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-3x mb-3"></i>
                <h5>Kriteria Kinerja</h5>
                <h3>{{ \App\Models\Kriteria::where('tipe', 'kinerja')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                <h5>Kriteria Kompetensi</h5>
                <h3>{{ \App\Models\Kriteria::where('tipe', 'kompetensi')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-list fa-3x mb-3"></i>
                <h5>Total Sub Kriteria</h5>
                <h3>{{ \App\Models\SubKriteria::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kriteria -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Kriteria</h5>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterTipeKriteria">
                    <option value="">Semua Tipe</option>
                    <option value="kinerja">Kinerja</option>
                    <option value="kompetensi">Kompetensi</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchKriteria" placeholder="Cari kriteria...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchKriteria" placeholder="Cari kriteria...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="kriteriaTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kriteria</th>
                        <th>Tipe</th>
                        <th>Bobot</th>
                        <th>Sub Kriteria</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Kriteria::with('subKriterias')->orderBy('nama')->get() as $index => $kriteria)
                        <tr data-tipe="{{ $kriteria->tipe }}">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $kriteria->nama }}</strong>
                                @if($kriteria->subKriterias->count() > 0)
                                    <br><small class="text-muted">{{ $kriteria->subKriterias->count() }} sub kriteria</small>
                                @endif
                            </td>
                            <td>
                                @if($kriteria->tipe == 'kinerja')
                                    <span class="badge bg-success">Kinerja</span>
                                @else
                                    <span class="badge bg-warning">Kompetensi</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ number_format($kriteria->bobot, 2) }}</span>
                            </td>
                            <td>
                                @if($kriteria->subKriterias->count() > 0)
                                    <button class="btn btn-sm btn-outline-info"
                                            onclick="showSubKriteria({{ $kriteria->id }})">
                                        <i class="fas fa-eye me-1"></i>{{ $kriteria->subKriterias->count() }}
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($kriteria->subKriterias->count() > 0)
                                    <span class="badge bg-success">Lengkap</span>
                                @else
                                    <span class="badge bg-warning">Belum Lengkap</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.kriteria.show', $kriteria->id) }}"
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kriteria.edit', $kriteria->id) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- <button class="btn btn-success btn-sm"
                                            onclick="duplicateKriteria({{ $kriteria->id }})" title="Duplikasi">
                                        <i class="fas fa-copy"></i>
                                    </button> --}}
                                    <form id="delete-form-{{ $kriteria->id }}" action="{{ route('admin.kriteria.destroy', $kriteria->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $kriteria->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data kriteria</p>
                                <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Kriteria Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Sub Kriteria -->
<div class="modal fade" id="subKriteriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sub Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="subKriteriaContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pegawai ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

function validateWeights() {
    const url = '{{ route("admin.kriteria.validate-weights") }}';
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            let msg = '';
            if (data.is_valid_kinerja) {
                msg += '✅ ' + data.message_kinerja + '<==>';
            } else {
                msg += '⚠️ ' + data.message_kinerja + '\\n';
            }
            if (data.is_valid_kompetensi) {
                msg += '✅ ' + data.message_kompetensi;
            } else {
                msg += '⚠️ ' + data.message_kompetensi;
            }
            alert(msg);
        })
        .catch(error => {
            alert('Terjadi kesalahan saat validasi bobot');
            console.error('Error:', error);
        });
}

function duplicateKriteria(id) {
    if (confirm('Apakah Anda yakin ingin menduplikasi kriteria ini?')) {
        window.location.href = `{{ url('admin/kriteria') }}/${id}/duplicate`;
    }
}

function showSubKriteria(id) {
    fetch(`{{ url('admin/kriteria') }}/${id}/sub-kriteria`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            let content = '<div class="table-responsive"><table class="table table-sm">';
            content += '<thead><tr><th>Nama</th><th>Bobot</th><th>Nilai Min</th><th>Nilai Max</th></tr></thead><tbody>';

            if (data.length > 0) {
                data.forEach(sub => {
                    content += `<tr>
                        <td>${sub.nama}</td>
                        <td><span class="badge bg-primary">${sub.bobot}</span></td>
                        <td>${sub.nilai_min || '-'}</td>
                        <td>${sub.nilai_max || '-'}</td>
                    </tr>`;
                });
            } else {
                content += '<tr><td colspan="4" class="text-center">Tidak ada sub kriteria</td></tr>';
            }

            content += '</tbody></table></div>';

            document.getElementById('subKriteriaContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('subKriteriaModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data sub kriteria');
        });
}


// Search functionality
document.getElementById('searchKriteria').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('kriteriaTable');
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

document.getElementById('filterTipeKriteria').addEventListener('change', function() {
    const tipe = this.value;
    const table = document.getElementById('kriteriaTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        if (!row.hasAttribute('data-tipe')) continue;

        const tipeRow = row.getAttribute('data-tipe');
        row.style.display = (!tipe || tipeRow === tipe) ? '' : 'none';
    }
});
</script>
@endsection
