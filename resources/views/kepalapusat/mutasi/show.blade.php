@extends('layouts.app')

@section('title', 'Detail Permohonan Mutasi')

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
    </ul>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-eye me-2"></i>Detail Permohonan Mutasi</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('kepalapusat.mutasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Informasi Pegawai dan Permohonan -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pegawai</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama:</strong></td>
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
                        <td><strong>Pendidikan:</strong></td>
                        <td>{{ $mutasiRequest->user->pendidikan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Masa Kerja:</strong></td>
                        <td>{{ $mutasiRequest->user->masa_kerja }} bulan</td>
                    </tr>
                    <tr>
                        <td><strong>Usia:</strong></td>
                        <td>{{ $mutasiRequest->user->usia }} tahun</td>
                    </tr>
                    @if($mutasiRequest->user->profile)
                    <tr>
                        <td><strong>No. HP:</strong></td>
                        <td>{{ $mutasiRequest->user->profile->no_hp }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $mutasiRequest->user->profile->alamat }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Permohonan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Tanggal Pengajuan:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($mutasiRequest->tanggal_pengajuan)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pendidikan Terakhir:</strong></td>
                        <td>{{ $mutasiRequest->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lokasi Tujuan:</strong></td>
                        <td>{{ $mutasiRequest->lokasi_tujuan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->status == 'menunggu' ? 'warning' :
                                ($mutasiRequest->status == 'diproses' ? 'info' :
                                ($mutasiRequest->status == 'diterima' ? 'success' :
                                ($mutasiRequest->status == 'ditolak' ? 'danger' : 'secondary')))
                            }} fs-6">
                                {{ ucfirst($mutasiRequest->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Keputusan:</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $mutasiRequest->keputusan_akhir == 'diterima' ? 'success' :
                                ($mutasiRequest->keputusan_akhir == 'ditolak' ? 'danger' : 'secondary')
                            }} fs-6">
                                {{ ucfirst($mutasiRequest->keputusan_akhir) }}
                            </span>
                        </td>
                    </tr>
                    @if($mutasiRequest->tanggal_keputusan)
                    <tr>
                        <td><strong>Tanggal Keputusan:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($mutasiRequest->tanggal_keputusan)->format('d F Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if($hasilAkhir)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-2x mb-2"></i>
                <h5>Nilai Akhir</h5>
                <h3>{{ number_format($hasilAkhir->nilai_akhir, 3) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-medal fa-2x mb-2"></i>
                <h5>Ranking</h5>
                <h3>{{ $hasilAkhir->ranking_akhir ?? '-' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                <h5>Lokasi Mutasi</h5>
                <h6>{{ $hasilAkhir->lokasi_mutasi ?? '-' }}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h5>Status</h5>
                <h6>{{ ucfirst($hasilAkhir->status) }}</h6>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Perhitungan ORESTE -->
@if($perhitunganOreste)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Hasil Perhitungan ORESTE</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-chart-line me-2"></i>Nilai Kinerja</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4 fw-bold">{{ number_format($perhitunganOreste->nilai_kinerja, 3) }}</span>
                            <span class="badge bg-primary fs-6">Ranking #{{ $perhitunganOreste->ranking_kinerja }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-star me-2"></i>Nilai Kompetensi</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-4 fw-bold">{{ number_format($perhitunganOreste->nilai_kompetensi, 3) }}</span>
                            <span class="badge bg-success fs-6">Ranking #{{ $perhitunganOreste->ranking_kompetensi }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-map-marker-alt me-2"></i>Rekomendasi Lokasi</h6>
                        <span class="fs-5">{{ $perhitunganOreste->rekomendasi_lokasi ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Status Mutasi ORESTE</h6>
                        <span class="fs-5">{{ ucfirst($perhitunganOreste->status_mutasi) ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistik Penilaian -->
@if($penilaians->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                <h5>Total Kriteria</h5>
                <h3>{{ $totalKriteria }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x mb-2"></i>
                <h5>Rata-rata</h5>
                <h3>{{ number_format($nilaiRataRata, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-arrow-up fa-2x mb-2"></i>
                <h5>Tertinggi</h5>
                <h3>{{ number_format($nilaiTertinggi, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-arrow-down fa-2x mb-2"></i>
                <h5>Terendah</h5>
                <h3>{{ number_format($nilaiTerendah, 2) }}</h3>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Detail Penilaian -->
@if($penilaians->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Detail Penilaian</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Nilai</th>
                        <th>Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penilaians as $index => $penilaian)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $penilaian->kriteria->nama }}</td>
                        <td>{{ $penilaian->subKriteria->nama }}</td>
                        <td>
                            <span class="badge bg-{{
                                $penilaian->nilai >= 80 ? 'success' :
                                ($penilaian->nilai >= 70 ? 'warning' : 'danger')
                            }}">
                                {{ $penilaian->nilai }}
                            </span>
                        </td>
                        <td>{{ $penilaian->kriteria->bobot }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Alasan Mutasi -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Alasan Mutasi</h5>
    </div>
    <div class="card-body">
        <p class="mb-0">{{ $mutasiRequest->alasan_mutasi }}</p>
    </div>
</div>

<!-- Update Status -->
@php
    $oreste = \App\Models\PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
        ->where('tahun', date('Y'))
        ->first();
    $hasRanking = $oreste && ($oreste->ranking_kinerja !== null || $oreste->ranking_kompetensi !== null);
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Update Status</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('kepalapusat.mutasi.update-status', $mutasiRequest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @if($mutasiRequest->status == 'menunggu')
                                <option value="diproses" selected>Diproses</option>
                            @elseif($mutasiRequest->status == 'diproses')
                                @if($hasRanking)
                                    <option value="diterima" {{ old('status', $mutasiRequest->status) == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ old('status', $mutasiRequest->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                @else
                                    <option value="" selected disabled>Belum bisa menentukan, ranking belum tersedia</option>
                                @endif
                            @elseif($mutasiRequest->status == 'diterima')
                                <option value="diterima" selected>Diterima</option>
                                <option value="selesai">Selesai</option>
                            @elseif($mutasiRequest->status == 'ditolak')
                                <option value="ditolak" selected>Ditolak</option>
                                <option value="selesai">Selesai</option>
                            @elseif($mutasiRequest->status == 'selesai')
                                <option value="selesai" selected>Selesai</option>
                            @else
                                <option value="{{ $mutasiRequest->status }}" selected>{{ ucfirst($mutasiRequest->status) }}</option>
                            @endif
                        </select>
                        @if(!$hasRanking && $mutasiRequest->status == 'diproses')
                            <div class="text-danger small mt-1">
                                Tidak bisa memilih "Diterima" atau "Ditolak" karena ranking kinerja/kompetensi belum tersedia.
                            </div>
                        @endif
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2" placeholder="Tambahkan keterangan status...">{{ old('keterangan', $mutasiRequest->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" {{ (!$hasRanking && $mutasiRequest->status == 'diproses') ? 'disabled' : '' }}>
                    <i class="fas fa-save me-1"></i>Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
