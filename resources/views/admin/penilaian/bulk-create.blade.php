@extends('layouts.app')

@section('title', 'Input Massal Penilaian')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
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
    <h4><i class="fas fa-upload me-2"></i>Input Massal Penilaian</h4>
    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Input Massal Penilaian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.penilaian.bulk-store') }}" method="POST" id="bulkForm">
            @csrf

            <!-- Filter dan Pengaturan -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Tahun Penilaian <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" value="{{ $tahun }}" min="2020" max="2030" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Filter Pegawai</label>
                        <select class="form-control" id="filterPegawai">
                            <option value="">Semua Pegawai</option>
                            @foreach($pegawai as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Filter Kriteria</label>
                        <select class="form-control" id="filterKriteria">
                            <option value="">Semua Kriteria</option>
                            @foreach($kriteria as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-success" onclick="generateForm()">
                                <i class="fas fa-magic me-2"></i>Generate Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Input Massal -->
            <div id="bulkFormContainer">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Pilih filter di atas dan klik "Generate Form" untuk membuat form input massal.
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    <i class="fas fa-save me-2"></i>Simpan Semua
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template untuk baris input -->
<template id="inputRowTemplate">
    <tr class="input-row">
        <td>
            <select class="form-control form-control-sm" name="penilaian[INDEX][user_id]" required>
                <option value="">Pilih Pegawai</option>
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->nip }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control form-control-sm kriteria-select" name="penilaian[INDEX][kriteria_id]" required>
                <option value="">Pilih Kriteria</option>
                @foreach($kriteria as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control form-control-sm sub-kriteria-select" name="penilaian[INDEX][sub_kriteria_id]" required>
                <option value="">Pilih Sub Kriteria</option>
            </select>
        </td>
        <td>
            {{-- <input type="number" class="form-control form-control-sm" name="penilaian[INDEX][nilai]"
                   min="0" max="100" placeholder="0-100" required> --}}
            <input type="number" class="form-control form-control-sm" name="penilaian[INDEX][nilai]" placeholder="Nilai" required>
            <div class="form-text text-muted range-info"></div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="penilaian[INDEX][catatan]"
                   placeholder="Catatan (opsional)">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let rowIndex = 0;

function generateForm() {
    const filterPegawai = document.getElementById('filterPegawai').value;
    const filterKriteria = document.getElementById('filterKriteria').value;
    const tahun = document.querySelector('input[name="tahun"]').value;

    if (!tahun) {
        alert('Tahun penilaian harus diisi!');
        return;
    }

    const container = document.getElementById('bulkFormContainer');
    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Pegawai</th>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Nilai (0-100)</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="inputRows">
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-success" onclick="addRow()">
                <i class="fas fa-plus me-2"></i>Tambah Baris
            </button>
            <button type="button" class="btn btn-info" onclick="addMultipleRows()">
                <i class="fas fa-layer-group me-2"></i>Tambah 5 Baris
            </button>
        </div>
    `;

    if (filterPegawai && filterKriteria) {
        addRow();
    } else if (filterPegawai) {
        const kriteria = @json($kriteria);
        kriteria.forEach(k => {
            addRow();
        });
    } else if (filterKriteria) {
        const pegawai = @json($pegawai);
        pegawai.forEach(p => {
            addRow();
        });
    } else {
        for (let i = 0; i < 3; i++) {
            addRow();
        }
    }

    document.getElementById('submitBtn').disabled = false;
}

function addRow() {
    const tbody = document.getElementById('inputRows');
    const template = document.getElementById('inputRowTemplate');
    const clone = template.content.cloneNode(true);

    const inputs = clone.querySelectorAll('select, input');
    inputs.forEach(input => {
        input.name = input.name.replace('INDEX', rowIndex);
    });

    const filterPegawai = document.getElementById('filterPegawai').value;
    const filterKriteria = document.getElementById('filterKriteria').value;

    if (filterPegawai) {
        clone.querySelector('select[name*="user_id"]').value = filterPegawai;
    }
    if (filterKriteria) {
        clone.querySelector('select[name*="kriteria_id"]').value = filterKriteria;
        setTimeout(() => {
            clone.querySelector('select[name*="kriteria_id"]').dispatchEvent(new Event('change'));
        }, 100);
    }

    tbody.appendChild(clone);
    rowIndex++;

    const kriteriaSelect = tbody.lastElementChild.querySelector('.kriteria-select');
    kriteriaSelect.addEventListener('change', function() {
        loadSubKriteria(this);
    });
}

function addMultipleRows() {
    for (let i = 0; i < 5; i++) {
        addRow();
    }
}

function removeRow(button) {
    button.closest('tr').remove();
}

// function loadSubKriteria(kriteriaSelect) {
//     const kriteriaId = kriteriaSelect.value;
//     const row = kriteriaSelect.closest('tr');
//     const subKriteriaSelect = row.querySelector('.sub-kriteria-select');

//     if (!kriteriaId) {
//         subKriteriaSelect.innerHTML = '<option value="">Pilih Sub Kriteria</option>';
//         return;
//     }

//     subKriteriaSelect.innerHTML = '<option value="">Memuat...</option>';

//     fetch(`{{ url('admin/penilaian/kriteria') }}/${kriteriaId}/sub-kriteria`)
//         .then(response => response.json())
//         .then(data => {
//             let options = '<option value="">Pilih Sub Kriteria</option>';
//             data.forEach(sub => {
//                 options += `<option value="${sub.id}">${sub.nama}</option>`;
//             });
//             subKriteriaSelect.innerHTML = options;
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             subKriteriaSelect.innerHTML = '<option value="">Error loading data</option>';
//         });
// }
function loadSubKriteria(kriteriaSelect) {
    const kriteriaId = kriteriaSelect.value;
    const row = kriteriaSelect.closest('tr');
    const subKriteriaSelect = row.querySelector('.sub-kriteria-select');
    const nilaiInput = row.querySelector('input[name*="nilai"]');
    const rangeInfoId = 'rangeInfo_' + Math.random().toString(36).substr(2, 9);

    if (!kriteriaId) {
        subKriteriaSelect.innerHTML = '<option value="">Pilih Sub Kriteria</option>';
        if (nilaiInput) {
            nilaiInput.removeAttribute('min');
            nilaiInput.removeAttribute('max');
        }
        return;
    }

    subKriteriaSelect.innerHTML = '<option value="">Memuat...</option>';

    fetch(`{{ url('admin/penilaian/kriteria') }}/${kriteriaId}/sub-kriteria`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Pilih Sub Kriteria</option>';
            data.forEach(sub => {
                options += `<option value="${sub.id}" data-min="${sub.nilai_min}" data-max="${sub.nilai_max}">
                    ${sub.nama} (Range: ${sub.nilai_min} - ${sub.nilai_max})
                </option>`;
            });
            subKriteriaSelect.innerHTML = options;

            // Event listener untuk update min/max nilai saat subkriteria dipilih
            subKriteriaSelect.addEventListener('change', function() {
                const selected = subKriteriaSelect.options[subKriteriaSelect.selectedIndex];
                const min = selected.getAttribute('data-min');
                const max = selected.getAttribute('data-max');
                if (min && max) {
                    nilaiInput.setAttribute('min', min);
                    nilaiInput.setAttribute('max', max);
                    // Tampilkan info range di bawah input nilai
                    if (!row.querySelector('.range-info')) {
                        const info = document.createElement('div');
                        info.className = 'form-text text-muted range-info';
                        info.id = rangeInfoId;
                        nilaiInput.parentNode.appendChild(info);
                    }
                    row.querySelector('.range-info').innerText = `Nilai harus di antara ${min} dan ${max}`;
                } else {
                    nilaiInput.removeAttribute('min');
                    nilaiInput.removeAttribute('max');
                    if (row.querySelector('.range-info')) {
                        row.querySelector('.range-info').innerText = '';
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            subKriteriaSelect.innerHTML = '<option value="">Error loading data</option>';
        });
}

document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.input-row');
    let hasData = false;

    rows.forEach(row => {
        const userSelect = row.querySelector('select[name*="user_id"]');
        const kriteriaSelect = row.querySelector('select[name*="kriteria_id"]');
        const subKriteriaSelect = row.querySelector('select[name*="sub_kriteria_id"]');
        const nilaiInput = row.querySelector('input[name*="nilai"]');

        if (userSelect.value && kriteriaSelect.value && subKriteriaSelect.value && nilaiInput.value) {
            hasData = true;
        }
    });

    if (!hasData) {
        e.preventDefault();
        alert('Minimal harus ada satu data penilaian yang diisi lengkap!');
        return false;
    }

    if (!confirm('Apakah Anda yakin ingin menyimpan semua data penilaian ini?')) {
        e.preventDefault();
        return false;
    }
});

document.getElementById('filterPegawai').addEventListener('change', function() {
    if (document.getElementById('inputRows')) {
        generateForm();
    }
});

document.getElementById('filterKriteria').addEventListener('change', function() {
    if (document.getElementById('inputRows')) {
        generateForm();
    }
});
</script>
@endsection
