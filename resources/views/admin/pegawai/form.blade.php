@extends('layouts.app')

@section('title', isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai')

@section('sidebar-menu')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
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
    <h4><i class="fas fa-user-plus me-2"></i>{{ isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai' }}</h4>
    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Pegawai</h5>
    </div>
    <div class="card-body">
        <form action="{{ isset($pegawai) ? route('admin.pegawai.update', $pegawai->id) : route('admin.pegawai.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($pegawai))
                @method('PUT')
            @endif

            <!-- Data User -->
            <h6 class="mb-3 text-primary"><i class="fas fa-user me-2"></i>Data Akun</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                               name="nip" value="{{ $pegawai->nip ?? old('nip') }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ $pegawai->name ?? old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ $pegawai->email ?? old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            Password {!! isset($pegawai) ? '' : '<span class="text-danger">*</span>' !!}
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" {{ isset($pegawai) ? '' : 'required' }}>
                        @if(isset($pegawai))
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        @endif
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            Konfirmasi Password {!! isset($pegawai) ? '' : '<span class="text-danger">*</span>' !!}
                        </label>
                        <input type="password" class="form-control" name="password_confirmation" {{ isset($pegawai) ? '' : 'required' }}>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach(\App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}"
                                    {{ ($pegawai->role_id ?? old('role_id')) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Pekerjaan -->
            <h6 class="mb-3 text-primary mt-4"><i class="fas fa-briefcase me-2"></i>Data Pekerjaan</h6>
            <div class="row">
                {{-- <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror"
                               name="jabatan" value="{{ $pegawai->jabatan ?? old('jabatan') }}" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                        <select class="form-control @error('pendidikan') is-invalid @enderror" name="pendidikan" required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="SMA/SMK" {{ ($pegawai->pendidikan ?? old('pendidikan')) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                            <option value="S1" {{ ($pegawai->pendidikan ?? old('pendidikan')) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ ($pegawai->pendidikan ?? old('pendidikan')) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ ($pegawai->pendidikan ?? old('pendidikan')) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('pendidikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Masa Kerja (bulan) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('masa_kerja') is-invalid @enderror"
                               name="masa_kerja" value="{{ $pegawai->masa_kerja ?? old('masa_kerja') }}" required>
                        @error('masa_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Usia <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('usia') is-invalid @enderror"
                               name="usia" value="{{ $pegawai->usia ?? old('usia') }}" required>
                        @error('usia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Pribadi -->
            <h6 class="mb-3 text-primary mt-4"><i class="fas fa-id-card me-2"></i>Data Pribadi</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                               name="tempat_lahir" value="{{ $pegawai->profile->tempat_lahir ?? old('tempat_lahir') }}">
                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               name="tanggal_lahir" value="{{ $pegawai->profile->tanggal_lahir ?? old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ ($pegawai->profile->jenis_kelamin ?? old('jenis_kelamin')) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ ($pegawai->profile->jenis_kelamin ?? old('jenis_kelamin')) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status Perkawinan</label>
                        <select class="form-control @error('status_perkawinan') is-invalid @enderror" name="status_perkawinan">
                            <option value="">Pilih Status</option>
                            <option value="Belum Menikah" {{ ($pegawai->profile->status_perkawinan ?? old('status_perkawinan')) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ ($pegawai->profile->status_perkawinan ?? old('status_perkawinan')) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Cerai" {{ ($pegawai->profile->status_perkawinan ?? old('status_perkawinan')) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        </select>
                        @error('status_perkawinan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Agama</label>
                        <input type="text" class="form-control @error('agama') is-invalid @enderror"
                               name="agama" value="{{ $pegawai->profile->agama ?? old('agama') }}">
                        @error('agama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                               name="no_hp" value="{{ $pegawai->profile->no_hp ?? old('no_hp') }}">
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  name="alamat" rows="3">{{ $pegawai->profile->alamat ?? old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control @error('foto') is-invalid @enderror"
                               name="foto" accept="image/*">
                        @if(isset($pegawai) && $pegawai->profile && $pegawai->profile->foto)
                            <small class="text-muted">Foto saat ini: {{ basename($pegawai->profile->foto) }}</small>
                        @endif
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($pegawai) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


