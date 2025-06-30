<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KepalaPusatController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PenilaianController;


// Login & Logout
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth','role:1'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/', function() { return view('admin.dashboard'); })->name('dashboard');
    Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('dashboard');

    // pegawai
    Route::get('/pegawai', [AdminController::class, 'indexPegawai'])->name('pegawai.index');
    Route::get('/create-pegawai', [AdminController::class, 'createPegawai'])->name('pegawai.create');
    Route::post('/store-pegawai', [AdminController::class, 'storePegawai'])->name('pegawai.store');
    Route::get('pegawai/{id}', [AdminController::class, 'showPegawai'])->name('pegawai.show');
    Route::get('pegawai-edit/{id}', [AdminController::class, 'editPegawai'])->name('pegawai.edit');
    Route::put('pegawai-update/{id}', [AdminController::class, 'updatePegawai'])->name('pegawai.update');
    Route::delete('/delete/pegawai/{id}', [AdminController::class, 'destroyPegawai'])->name('pegawai.destroy');

    // mutasi
    Route::get('/mutasi', [AdminController::class, 'lihatPermohonanMutasi'])->name('mutasi.index');
    Route::get('/mutasi/export-excel', [AdminController::class, 'exportExcel'])->name('mutasi.export-excel');
    Route::get('/mutasi/export-pdf', [AdminController::class, 'exportPdf'])->name('mutasi.export-pdf');
    Route::get('/mutasi/{id}', [AdminController::class, 'showMutasi'])->name('mutasi.show');

    // kriteria
    Route::get('/kriteria', [AdminController::class, 'indexKriteria'])->name('kriteria.index');
    Route::get('/create-kriteria', [AdminController::class, 'createKriteria'])->name('kriteria.create');
    Route::post('/store-kriteria', [AdminController::class, 'storeKriteria'])->name('kriteria.store');
    Route::get('kriteria/validate-weights', [AdminController::class, 'validateKriteriaWeights'])->name('kriteria.validate-weights');
    Route::get('kriteria/{id}', [AdminController::class, 'showKriteria'])->name('kriteria.show');
    Route::get('kriteria-edit/{id}', [AdminController::class, 'editKriteria'])->name('kriteria.edit');
    Route::put('kriteria-update/{id}', [AdminController::class, 'updateKriteria'])->name('kriteria.update');
    Route::delete('/delete/kriteria/{id}', [AdminController::class, 'destroyKriteria'])->name('kriteria.destroy');
    Route::get('kriteria/{id}/sub-kriteria', [AdminController::class, 'getSubKriteria'])->name('kriteria.sub-kriteria');


    // Penilaian
    Route::get('/penilaian', [AdminController::class, 'indexPenilaian'])->name('penilaian.index');
    Route::get('/create-penilaian', [AdminController::class, 'createPenilaian'])->name('penilaian.create');
    Route::post('/penilaian-kriteria', [AdminController::class, 'storePenilaian'])->name('penilaian.store');
    Route::get('penilaian/{id}', [AdminController::class, 'showPenilaian'])->name('penilaian.show');
    Route::get('penilaian/{id}/edit', [AdminController::class, 'editPenilaian'])->name('penilaian.edit');
    Route::put('penilaian/{id}', [AdminController::class, 'updatePenilaian'])->name('penilaian.update');
    Route::delete('penilaian/{id}', [AdminController::class, 'destroyPenilaian'])->name('penilaian.destroy');
    Route::get('penilaian/pegawai/{user}', [AdminController::class, 'getPegawaiDetail'])->name('penilaian.pegawai.detail');
    Route::get('/admin/penilaian/validate-completeness', [AdminController::class, 'validateCompletenessPenilaian'])
    ->name('penilaian.validate-completeness');
    Route::get('penilaian/bulk/create', [AdminController::class, 'bulkCreatePenilaian'])->name('penilaian.bulk-create');
    Route::post('penilaian/bulk', [AdminController::class, 'storeBulkPenilaian'])->name('penilaian.bulk-store');
    Route::get('penilaian/kriteria/{id}/sub-kriteria', [AdminController::class, 'getSubKriteriaByKriteria'])->name('penilaian.sub-kriteria');

    // Perhitungan Oreste Routes
    Route::get('perhitungan', [AdminController::class, 'indexPerhitungan'])->name('perhitungan.index');
    Route::get('perhitungan/export', [AdminController::class, 'exportPerhitungan'])->name('perhitungan.export');
    Route::post('perhitungan/proses', [AdminController::class, 'prosesPerhitungan'])->name('perhitungan.proses');
    Route::post('perhitungan/reset', [AdminController::class, 'resetPerhitungan'])->name('perhitungan.reset');
    Route::get('perhitungan/{id}', [AdminController::class, 'showPerhitungan'])->name('perhitungan.show');

    // Hasil Akhir Routes
    Route::get('hasil-akhir', [AdminController::class, 'indexHasilAkhir'])->name('hasil-akhir.index');
    Route::get('hasil-akhir/{id}', [AdminController::class, 'showHasilAkhir'])->name('hasil-akhir.show');

});

// Pegawai
Route::middleware(['auth','role:2'])->prefix('pegawai')->name('pegawai.')->group(function() {
    Route::get('/dashboard', function() { return view('pegawai.dashboard'); })->name('dashboard');
    Route::get('/mutasi/ajukan', [PegawaiController::class, 'ajukanMutasi'])->name('mutasi.ajukan');

    // Profil routes
    Route::get('/profil', [PegawaiController::class, 'profil'])->name('profil.index');
    Route::get('/profil/edit', [PegawaiController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil/update', [PegawaiController::class, 'updateProfilData'])->name('profil.update');

    // Mutasi routes
    Route::get('/mutasi', [PegawaiController::class, 'indexMutasi'])->name('mutasi.index');
    Route::get('/mutasi/create', [PegawaiController::class, 'createMutasi'])->name('mutasi.create');
    Route::post('/mutasi', [PegawaiController::class, 'storeMutasi'])->name('mutasi.store');
    Route::get('/mutasi/{id}', [PegawaiController::class, 'showMutasi'])->name('mutasi.show');
    Route::get('/mutasi/{id}/edit', [PegawaiController::class, 'editMutasi'])->name('mutasi.edit');
    Route::put('/mutasi/{id}', [PegawaiController::class, 'updateMutasi'])->name('mutasi.update');
    Route::delete('/mutasi/{id}', [PegawaiController::class, 'destroyMutasi'])->name('mutasi.destroy');

    // Penilaian routes
    Route::get('/penilaian', [PegawaiController::class, 'indexPenilaian'])->name('penilaian.index');
    Route::get('/penilaian/{id}', [PegawaiController::class, 'showPenilaian'])->name('penilaian.show');
    Route::get('pegawai/penilaian/export-pdf', [PegawaiController::class,'exportPenilaianPdf'])->name('penilaian.export-pdf');

    // Hasil Akhir routes
    Route::get('/hasil-akhir', [PegawaiController::class, 'indexHasilAkhir'])->name('hasil-akhir.index');
    Route::get('/hasil-akhir/{id}', [PegawaiController::class, 'showHasilAkhir'])->name('hasil-akhir.show');

});

// Kepala Pusat
Route::middleware(['auth','role:3'])->prefix('kepalapusat')->name('kepalapusat.')->group(function() {
    Route::get('/dashboard', function() { return view('kepalapusat.dashboard'); })->name('dashboard');

    // Mutasi routes
    Route::get('/mutasi/export-excel', [KepalaPusatController::class, 'exportExcel'])->name('mutasi.export-excel-pusat');
    Route::get('/mutasi', [KepalaPusatController::class, 'indexMutasi'])->name('mutasi.index');
    Route::get('/mutasi/{id}', [KepalaPusatController::class, 'showMutasi'])->name('mutasi.show');
    Route::get('/mutasi/{id}/edit', [KepalaPusatController::class, 'editMutasi'])->name('mutasi.edit');
    Route::put('/mutasi/{id}', [KepalaPusatController::class, 'updateMutasi'])->name('mutasi.update');
    Route::post('/mutasi/{id}/approve', [KepalaPusatController::class, 'approveMutasi'])->name('mutasi.approve');
    Route::post('/mutasi/{id}/reject', [KepalaPusatController::class, 'rejectMutasi'])->name('mutasi.reject');
    // Route::get('/mutasi/export', [KepalaPusatController::class, 'exportMutasi'])->name('mutasi.export');
    Route::put('/mutasi/{id}/status', [KepalaPusatController::class, 'updateStatusMutasi'])->name('mutasi.update-status');
});
