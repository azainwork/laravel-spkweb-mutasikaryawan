@extends('layouts.app')

@section('title', 'Edit Permohonan Mutasi')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.profil.*') ? 'active' : '' }}" href="{{ route('pegawai.profil.index') }}">
                <i class="fas fa-user"></i>
                Profil Saya
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.mutasi.*') ? 'active' : '' }}" href="{{ route('pegawai.mutasi.index') }}">
                <i class="fas fa-file-alt"></i>
                Permohonan Mutasi
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-edit me-2"></i>Edit Permohonan Mutasi</h4>
    <a href="{{ route('pegawai.mutasi.show', $mutasiRequest->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $mutasiRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>{{ $mutasiRequest->user->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan:</strong></td>
                        <td>{{ $mutasiRequest->user->jabatan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-warning">{{ ucfirst($mutasiRequest->status) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Permohonan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pegawai.mutasi.update', $mutasiRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                        <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="SD" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D3" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir', $mutasiRequest->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('pendidikan_terakhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lokasi_tujuan" class="form-label">Lokasi Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi_tujuan') is-invalid @enderror" id="lokasi_tujuan" name="lokasi_tujuan" value="{{ old('lokasi_tujuan', $mutasiRequest->lokasi_tujuan) }}" placeholder="Contoh: Jakarta, Bandung, Surabaya" required>
                        @error('lokasi_tujuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alasan_mutasi" class="form-label">Alasan Mutasi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan_mutasi') is-invalid @enderror" id="alasan_mutasi" name="alasan_mutasi" rows="4" placeholder="Jelaskan alasan mengajukan mutasi..." required>{{ old('alasan_mutasi', $mutasiRequest->alasan_mutasi) }}</textarea>
                        @error('alasan_mutasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimal 10 karakter</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pegawai.mutasi.show', $mutasiRequest->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
