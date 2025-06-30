@extends('layouts.app')

@section('title', 'Edit Permohonan Mutasi')

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
            <a class="nav-link {{ request()->routeIs('kepalapusat.pegawai.*') ? 'active' : '' }}" href="{{ route('kepalapusat.pegawai.index') }}">
                <i class="fas fa-users"></i>
                Data Pegawai
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.penilaian.*') ? 'active' : '' }}" href="{{ route('kepalapusat.penilaian.index') }}">
                <i class="fas fa-chart-bar"></i>
                Data Penilaian
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('kepalapusat.hasil-akhir.index') }}">
                <i class="fas fa-trophy"></i>
                Hasil Akhir
            </a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kepalapusat.laporan.*') ? 'active' : '' }}" href="{{ route('kepalapusat.laporan.index') }}">
                <i class="fas fa-file-pdf"></i>
                Laporan
            </a>
        </li> --}}
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-edit me-2"></i>Edit Permohonan Mutasi</h4>
    <a href="{{ route('kepalapusat.mutasi.show', $mutasiRequest->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pegawai</h5>
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
                        <td><strong>Status Saat Ini:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->status == 'menunggu' ? 'warning' :
                                ($mutasiRequest->status == 'diproses' ? 'info' :
                                ($mutasiRequest->status == 'diterima' ? 'success' :
                                ($mutasiRequest->status == 'ditolak' ? 'danger' : 'secondary')))
                            }}">
                                {{ ucfirst($mutasiRequest->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kepalapusat.mutasi.update', $mutasiRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="menunggu" {{ old('status', $mutasiRequest->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ old('status', $mutasiRequest->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="diterima" {{ old('status', $mutasiRequest->status) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ old('status', $mutasiRequest->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="selesai" {{ old('status', $mutasiRequest->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keputusan_akhir" class="form-label">Keputusan Akhir <span class="text-danger">*</span></label>
                                <select class="form-select @error('keputusan_akhir') is-invalid @enderror" id="keputusan_akhir" name="keputusan_akhir" required>
                                    <option value="belum" {{ old('keputusan_akhir', $mutasiRequest->keputusan_akhir) == 'belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="diterima" {{ old('keputusan_akhir', $mutasiRequest->keputusan_akhir) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ old('keputusan_akhir', $mutasiRequest->keputusan_akhir) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('keputusan_akhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="lokasi_mutasi" class="form-label">Lokasi Mutasi</label>
                        <input type="text" class="form-control @error('lokasi_mutasi') is-invalid @enderror" id="lokasi_mutasi" name="lokasi_mutasi" value="{{ old('lokasi_mutasi', $mutasiRequest->lokasi_mutasi) }}" placeholder="Contoh: Jakarta, Bandung, Surabaya">
                        @error('lokasi_mutasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan keterangan keputusan...">{{ old('keterangan', $mutasiRequest->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('kepalapusat.mutasi.show', $mutasiRequest->id) }}" class="btn btn-secondary">
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
