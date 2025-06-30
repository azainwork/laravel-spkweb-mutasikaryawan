@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-plus-circle me-2"></i>Tambah Kriteria</h4>
    <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0">Form Kriteria Baru</h5></div>
    <div class="card-body">
        <form action="{{ route('admin.kriteria.store') }}" method="POST">
            @csrf

            <h6 class="text-primary">Data Kriteria Utama</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe') is-invalid @enderror" name="tipe" required>
                        <option value="">Pilih Tipe</option>
                        <option value="kinerja" {{ old('tipe') == 'kinerja' ? 'selected' : '' }}>Kinerja</option>
                        <option value="kompetensi" {{ old('tipe') == 'kompetensi' ? 'selected' : '' }}>Kompetensi</option>
                    </select>
                    @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Bobot <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('bobot') is-invalid @enderror" name="bobot" value="{{ old('bobot') }}" step="0.01" min="0" max="1" required>
                    @error('bobot')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr>

            <h6 class="text-primary">Sub Kriteria</h6>
            {{-- <div id="sub-kriteria-wrapper">
            </div>
            <button type="button" id="add-sub-kriteria-btn" class="btn btn-sm btn-success mt-2">
                <i class="fas fa-plus"></i> Tambah Sub Kriteria
            </button> --}}

            <div id="sub-kriteria-wrapper">
                {{-- Kosong saat create --}}
            </div>
            <button type="button" id="add-sub-kriteria-btn" class="btn btn-sm btn-success mt-2">
                <i class="fas fa-plus"></i> Tambah Sub Kriteria
            </button>

            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>

document.getElementById('add-sub-kriteria-btn').addEventListener('click', function() {
    const wrapper = document.getElementById('sub-kriteria-wrapper');
    const newRow = document.createElement('div');
    newRow.className = 'input-group mb-2';
    newRow.innerHTML = `
        <input type="text" name="sub_kriteria_nama[]" class="form-control" placeholder="Nama Pilihan (e.g., Baik)" required>
        <input type="number" name="sub_kriteria_nilai_min[]" class="form-control" placeholder="Nilai Min (e.g., 80)" required>
        <input type="number" name="sub_kriteria_nilai_max[]" class="form-control" placeholder="Nilai Max (e.g., 89)" required>
        <input type="number" name="sub_kriteria_bobot[]" class="form-control" placeholder="Bobot (opsional)" step="0.01" min="0" max="1">
        <button type="button" class="btn btn-danger" onclick="this.closest('.input-group').remove()">Hapus</button>
    `;
    wrapper.appendChild(newRow);
});
// document.getElementById('add-sub-kriteria-btn').addEventListener('click', function() {
//     const wrapper = document.getElementById('sub-kriteria-wrapper');
//     const newRow = document.createElement('div');
//     newRow.className = 'input-group mb-2';
//     newRow.innerHTML = `
//         <input type="text" name="sub_kriteria_nama[]" class="form-control" placeholder="Nama Pilihan (e.g., Baik)" required>
//         <input type="number" name="sub_kriteria_nilai[]" class="form-control" placeholder="Nilai Tetap (e.g., 80)" required>
//         <button type="button" class="btn btn-danger" onclick="this.closest('.input-group').remove()">Hapus</button>
//     `;
//     wrapper.appendChild(newRow);
// });
</script>
@endsection
