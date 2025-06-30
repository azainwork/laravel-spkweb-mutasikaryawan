@extends('layouts.app')
@section('title', 'Tambah Penilaian')
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
            <a class="nav-link active" href="{{ route('admin.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-calculator"></i>
                Perhitungan Oreste
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li>
    </ul>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-plus me-2"></i>Tambah Penilaian</h4>
    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Form Penilaian</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.penilaian.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pegawai <span class="text-danger">*</span></label>
                            <select class="form-select" name="user_id" id="pegawaiSelect" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach($pegawai as $p)
                                    <option value="{{ $p->id }}" {{ old('user_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->nip }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" value="{{ old('tahun', date('Y')) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kriteria <span class="text-danger">*</span></label>
                            <select class="form-select" name="kriteria_id" id="kriteriaSelect" required>
                                <option value="">Pilih Kriteria</option>
                                @foreach($kriteria as $k)
                                    <option value="{{ $k->id }}" {{ old('kriteria_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sub Kriteria / Penilaian <span class="text-danger">*</span></label>
                            <select class="form-select" name="sub_kriteria_id" id="subKriteriaSelect" required>
                                <option value="">Pilih Kriteria terlebih dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="nilai" id="nilaiInput" required>
                            <div id="rangeInfo" class="form-text text-muted"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Detail Profil Pegawai</h5></div>
            <div class="card-body" id="pegawaiDetailCard">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Pilih seorang pegawai untuk melihat detailnya di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kriteriaSelect = document.getElementById('kriteriaSelect');
    const subKriteriaSelect = document.getElementById('subKriteriaSelect');
    const oldSubKriteriaId = "{{ old('sub_kriteria_id') }}";
    const pegawaiSelect = document.getElementById('pegawaiSelect');
    const detailCard = document.getElementById('pegawaiDetailCard');

    function fetchPegawaiDetail() {
        const userId = pegawaiSelect.value;
        if (!userId) {
            detailCard.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-info-circle fa-2x mb-2"></i><p>Pilih seorang pegawai untuk melihat detailnya di sini.</p></div>`;
            return;
        }

        detailCard.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat detail...</p></div>`;

        fetch(`{{ url('admin/penilaian/pegawai') }}/${userId}`)
            .then(response => response.json())
            .then(data => {
                detailCard.innerHTML = `
                    <table class="table table-sm table-borderless">
                        <tr><td width="40%"><strong>NIP</strong></td><td>: ${data.nip}</td></tr>
                        <tr><td><strong>Jabatan</strong></td><td>: ${data.jabatan}</td></tr>
                        <tr><td><strong>Pendidikan</strong></td><td>: ${data.pendidikan}</td></tr>
                        <tr><td><strong>Masa Kerja</strong></td><td>: ${data.masa_kerja}</td></tr>
                        <tr><td><strong>Usia</strong></td><td>: ${data.usia}</td></tr>
                        <tr><td><strong>No. HP</strong></td><td>: ${data.no_hp}</td></tr>
                        <tr><td><strong>Status</strong></td><td>: ${data.status_perkawinan}</td></tr>
                        <tr><td class="align-top"><strong>Alamat</strong></td><td class="align-top">: ${data.alamat}</td></tr>
                    </table>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                detailCard.innerHTML = `<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><p>Gagal memuat detail pegawai.</p></div>`;
            });
    }

    pegawaiSelect.addEventListener('change', fetchPegawaiDetail);

    if (pegawaiSelect.value) {
        fetchPegawaiDetail();
    }

    function fetchSubKriteria() {
        const kriteriaId = kriteriaSelect.value;
        if (!kriteriaId) {
            subKriteriaSelect.innerHTML = '<option value="">Pilih Kriteria terlebih dahulu</option>';
            return;
        }

        subKriteriaSelect.innerHTML = '<option value="">Memuat...</option>';

        fetch(`{{ url('admin/penilaian/kriteria') }}/${kriteriaId}/sub-kriteria`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Pilih Penilaian</option>';
                data.forEach(sub => {
                    options += `<option value="${sub.id}" data-min="${sub.nilai_min}" data-max="${sub.nilai_max}">
                                    ${sub.nama} (Range: ${sub.nilai_min} - ${sub.nilai_max})
                                </option>`;
                });
                subKriteriaSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Error:', error);
                subKriteriaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    subKriteriaSelect.addEventListener('change', function() {
        const selected = subKriteriaSelect.options[subKriteriaSelect.selectedIndex];
        const min = selected.getAttribute('data-min');
        const max = selected.getAttribute('data-max');
        if (min && max) {
            document.getElementById('rangeInfo').innerText = `Nilai harus di antara ${min} dan ${max}`;
            document.getElementById('nilaiInput').setAttribute('min', min);
            document.getElementById('nilaiInput').setAttribute('max', max);
        } else {
            document.getElementById('rangeInfo').innerText = '';
            document.getElementById('nilaiInput').removeAttribute('min');
            document.getElementById('nilaiInput').removeAttribute('max');
        }
    });

    kriteriaSelect.addEventListener('change', fetchSubKriteria);


    if (kriteriaSelect.value) {
        fetchSubKriteria();
    }
});
</script>
@endsection
