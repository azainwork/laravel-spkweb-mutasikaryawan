@extends('layouts.app')

@section('title', 'Edit Profil')

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
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-edit me-2"></i>Edit Profil</h4>
    <a href="{{ route('pegawai.profil.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Form Edit Profil</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('pegawai.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="mb-3 text-center">
                    @if($user->profile && $user->profile->foto)
                        <img src="{{ asset('storage/' . $user->profile->foto) }}" alt="Foto Profil" class="rounded-circle mb-2" width="120" height="120" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2" style="width: 120px; height: 120px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                    @endif
                    <div>
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatanOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ ($pegawai->jabatan ?? old('jabatan')) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="pendidikan" class="form-label">Pendidikan <span class="text-danger">*</span></label>
                        <select class="form-select @error('pendidikan') is-invalid @enderror" id="pendidikan" name="pendidikan" required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="SD" {{ old('pendidikan', $user->pendidikan) == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan', $user->pendidikan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan', $user->pendidikan) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D3" {{ old('pendidikan', $user->pendidikan) == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan', $user->pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan', $user->pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan', $user->pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="usia" class="form-label">Usia <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('usia') is-invalid @enderror" id="usia" name="usia" value="{{ old('usia', $user->usia) }}" min="18" max="65" required>
                        @error('usia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-3"><i class="fas fa-user-details me-2"></i>Informasi Pribadi</h6>

                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $user->profile->tempat_lahir ?? '') }}" required>
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->profile->tanggal_lahir ?? '') }}" required>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status_perkawinan" class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_perkawinan') is-invalid @enderror" id="status_perkawinan" name="status_perkawinan" required>
                            <option value="">Pilih Status</option>
                            <option value="Belum Menikah" {{ old('status_perkawinan', $user->profile->status_perkawinan ?? '') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ old('status_perkawinan', $user->profile->status_perkawinan ?? '') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Cerai" {{ old('status_perkawinan', $user->profile->status_perkawinan ?? '') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        </select>
                        @error('status_perkawinan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                        <select class="form-select @error('agama') is-invalid @enderror" id="agama" name="agama" required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama', $user->profile->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $user->profile->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $user->profile->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $user->profile->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $user->profile->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $user->profile->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h6 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Informasi Kontak</h6>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->profile->no_hp ?? '') }}" placeholder="Contoh: 081234567890" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $user->profile->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pegawai.profil.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
