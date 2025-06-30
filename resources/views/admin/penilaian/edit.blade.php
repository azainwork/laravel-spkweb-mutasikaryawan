@extends('layouts.app')

@section('title', 'Edit Penilaian')

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
    <h4><i class="fas fa-edit me-2"></i>Edit Penilaian</h4>
    <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Form Edit Penilaian</h5></div>
            <div class="card-body">
                <form action="{{ route('admin.penilaian.update', $penilaian->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pegawai <span class="text-danger">*</span></label>
                            <select class="form-select" name="user_id" id="pegawaiSelect" required>
                                @foreach($pegawai as $p)
                                    <option value="{{ $p->id }}" {{ old('user_id', $penilaian->user_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->nip }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" value="{{ old('tahun', $penilaian->tahun) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kriteria <span class="text-danger">*</span></label>
                            <select class="form-select" name="kriteria_id" id="kriteriaSelect" required>
                                <option value="">Pilih Kriteria</option>
                                @foreach($kriteria as $k)
                                    <option value="{{ $k->id }}" data-subkriteria='{{ json_encode($k->subKriterias) }}'
                                        {{ old('kriteria_id', $penilaian->kriteria_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
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
                            <input type="number" class="form-control" name="nilai" id="nilaiInput"
                                value="{{ old('nilai', $penilaian->nilai) }}" required>
                            <div id="rangeInfo" class="form-text text-muted"></div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kriteria <span class="text-danger">*</span></label>
                            <select class="form-select" name="kriteria_id" id="kriteriaSelect" required>
                                <option value="">Pilih Kriteria</option>
                                @foreach($kriteria as $k)
                                    <option value="{{ $k->id }}" data-subkriteria='{{ json_encode($k->subKriterias) }}' {{ old('kriteria_id', $penilaian->kriteria_id) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sub Kriteria / Penilaian <span class="text-danger">*</span></label>
                            <select class="form-select" name="sub_kriteria_id" id="subKriteriaSelect" required>
                                <option value="">Pilih Kriteria terlebih dahulu</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $penilaian->catatan) }}</textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Kolom Detail Profil Pegawai --}}
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
    const pegawaiSelect = document.getElementById('pegawaiSelect');
    const detailCard = document.getElementById('pegawaiDetailCard');
    const kriteriaSelect = document.getElementById('kriteriaSelect');
    const subKriteriaSelect = document.getElementById('subKriteriaSelect');

    const initialSubKriteriaId = '{{ old('sub_kriteria_id', $penilaian->sub_kriteria_id) }}';

    // function populateSubKriteria() {
    //     const selectedKriteria = kriteriaSelect.options[kriteriaSelect.selectedIndex];
    //     subKriteriaSelect.innerHTML = '<option value="">Pilih Kriteria terlebih dahulu</option>';

    //     if (!selectedKriteria || !selectedKriteria.value) {
    //         return;
    //     }

    //     try {
    //         const subKriterias = JSON.parse(selectedKriteria.dataset.subkriteria || '[]');
    //         let options = '<option value="">Pilih Sub Kriteria</option>';
    //         subKriterias.forEach(sub => {
    //             const selected = sub.id == initialSubKriteriaId ? 'selected' : '';
    //             options += `<option value="${sub.id}" ${selected}>${sub.nama} (Nilai: ${sub.nilai})</option>`;
    //         });
    //         subKriteriaSelect.innerHTML = options;
    //     } catch (e) {
    //         console.error('Gagal parsing data sub-kriteria:', e);
    //         subKriteriaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
    //     }
    // }

    function populateSubKriteria() {
        const selectedKriteria = kriteriaSelect.options[kriteriaSelect.selectedIndex];
        subKriteriaSelect.innerHTML = '<option value="">Pilih Kriteria terlebih dahulu</option>';

        if (!selectedKriteria || !selectedKriteria.value) {
            return;
        }

        try {
            const subKriterias = JSON.parse(selectedKriteria.dataset.subkriteria || '[]');
            let options = '<option value="">Pilih Sub Kriteria</option>';
            subKriterias.forEach(sub => {
                const selected = sub.id == initialSubKriteriaId ? 'selected' : '';
                // Tambahkan data-min dan data-max
                options += `<option value="${sub.id}" data-min="${sub.nilai_min}" data-max="${sub.nilai_max}" ${selected}>
                    ${sub.nama} (Range: ${sub.nilai_min} - ${sub.nilai_max})
                </option>`;
            });
            subKriteriaSelect.innerHTML = options;
            updateRangeInfo();
        } catch (e) {
            console.error('Gagal parsing data sub-kriteria:', e);
            subKriteriaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
        }
    }

    function updateRangeInfo() {
        const selected = subKriteriaSelect.options[subKriteriaSelect.selectedIndex];
        const min = selected ? selected.getAttribute('data-min') : '';
        const max = selected ? selected.getAttribute('data-max') : '';
        const rangeInfo = document.getElementById('rangeInfo');
        const nilaiInput = document.getElementById('nilaiInput');
        if (min && max) {
            rangeInfo.innerText = `Nilai harus di antara ${min} dan ${max}`;
            nilaiInput.setAttribute('min', min);
            nilaiInput.setAttribute('max', max);
        } else {
            rangeInfo.innerText = '';
            nilaiInput.removeAttribute('min');
            nilaiInput.removeAttribute('max');
        }
    }

    // Event listener untuk update range saat subkriteria berubah
    subKriteriaSelect.addEventListener('change', updateRangeInfo);

    function fetchPegawaiDetail() {
        const userId = pegawaiSelect.value;
        if (!userId) {
            detailCard.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-info-circle fa-2x mb-2"></i><p>Pilih seorang pegawai untuk melihat detailnya.</p></div>`;
            return;
        }

        detailCard.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat detail...</p></div>`;

        fetch(`{{ url('/admin/penilaian/pegawai') }}/${userId}`)
            .then(response => response.ok ? response.json() : Promise.reject('Gagal mengambil data'))
            .then(data => {
                detailCard.innerHTML = `
                    <table class="table table-sm table-borderless">
                        <tr><td width="40%"><strong>NIP</strong></td><td>: ${data.nip || '-'}</td></tr>
                        <tr><td><strong>Jabatan</strong></td><td>: ${data.jabatan || '-'}</td></tr>
                        <tr><td><strong>Pendidikan</strong></td><td>: ${data.pendidikan || '-'}</td></tr>
                        <tr><td><strong>Masa Kerja</strong></td><td>: ${data.masa_kerja || '-'} tahun</td></tr>
                        <tr><td><strong>Usia</strong></td><td>: ${data.usia || '-'} tahun</td></tr>
                        <tr><td><strong>No. HP</strong></td><td>: ${data.no_hp || '-'}</td></tr>
                        <tr><td><strong>Status</strong></td><td>: ${data.status_perkawinan || '-'}</td></tr>
                        <tr><td class="align-top"><strong>Alamat</strong></td><td class="align-top">: ${data.alamat || '-'}</td></tr>
                    </table>
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                detailCard.innerHTML = `<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><p>Gagal memuat detail pegawai.</p></div>`;
            });
    }

    kriteriaSelect.addEventListener('change', populateSubKriteria);
    pegawaiSelect.addEventListener('change', fetchPegawaiDetail);

    fetchPegawaiDetail();
    populateSubKriteria();
});
</script>
@endsection
