<?php

namespace App\Http\Controllers;

use App\Exports\MutasiRequestExport;
use App\Models\HasilAkhir;
use Illuminate\Http\Request;
use App\Models\MutasiRequest;
use App\Models\PerhitunganOreste;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class KepalaPusatController extends Controller
{

    public function indexMutasi()
    {
        $mutasiRequests = MutasiRequest::with(['user', 'user.profile'])
            ->orderByDesc('created_at')
            ->get();

        $totalPermohonan = $mutasiRequests->count();
        $menunggu = $mutasiRequests->where('status', 'menunggu')->count();
        $diterima = $mutasiRequests->where('status', 'diterima')->count();
        $ditolak = $mutasiRequests->where('status', 'ditolak')->count();
        $diproses = $mutasiRequests->where('status', 'diproses')->count();

        return view('kepalapusat.mutasi.index', compact('mutasiRequests', 'totalPermohonan', 'menunggu', 'diterima', 'ditolak', 'diproses'));
    }

    public function showMutasi($id)
    {
        $mutasiRequest = MutasiRequest::with(['user', 'user.profile'])
            ->findOrFail($id);

        $penilaians = \App\Models\Penilaian::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->with(['kriteria', 'subKriteria'])
            ->get();

        $perhitunganOreste = \App\Models\PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        $hasilAkhir = \App\Models\HasilAkhir::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        $totalKriteria = $penilaians->count();
        $nilaiRataRata = $penilaians->avg('nilai');
        $nilaiTertinggi = $penilaians->max('nilai');
        $nilaiTerendah = $penilaians->min('nilai');

        return view('kepalapusat.mutasi.show', compact(
            'mutasiRequest',
            'penilaians',
            'perhitunganOreste',
            'hasilAkhir',
            'totalKriteria',
            'nilaiRataRata',
            'nilaiTertinggi',
            'nilaiTerendah'
        ));
    }

    public function updateStatusMutasi(Request $request, $id)
    {
        $mutasiRequest = MutasiRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,diproses,diterima,ditolak,selesai',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $currentStatus = $mutasiRequest->status;
        $newStatus = $request->status;

        $allowedTransitions = [
            'menunggu' => ['diproses'],
            'diproses' => ['diterima', 'ditolak'],
            'diterima' => ['selesai'],
            'ditolak' => ['selesai'],
            'selesai' => []
        ];

        if (!in_array($newStatus, $allowedTransitions[$currentStatus])) {
            return redirect()->back()->with('error', 'Perubahan status tidak valid!');
        }

        if (in_array($newStatus, ['diterima', 'ditolak'])) {
            $oreste = \App\Models\PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
                ->where('tahun', date('Y'))
                ->first();
            if (!$oreste || ($oreste->ranking_kinerja === null && $oreste->ranking_kompetensi === null)) {
                return redirect()->back()->with('error', 'Tidak bisa mengubah status ke Diterima/Ditolak karena ranking kinerja/kompetensi belum tersedia.');
            }
        }

        $updateData = [
            'status' => $newStatus,
            'keterangan' => $request->keterangan,
        ];

        if (in_array($newStatus, ['diterima', 'ditolak'])) {
            $updateData['keputusan_akhir'] = $newStatus;
            $updateData['tanggal_keputusan'] = now();
        }

        $mutasiRequest->update($updateData);

        $statusMessages = [
            'diproses' => 'Permohonan mutasi sedang diproses',
            'diterima' => 'Permohonan mutasi disetujui',
            'ditolak' => 'Permohonan mutasi ditolak',
            'selesai' => 'Proses mutasi selesai'
        ];

        $message = $statusMessages[$newStatus] ?? 'Status berhasil diperbarui';

        return redirect()->route('kepalapusat.mutasi.show', $id)->with('success', $message);
    }

    public function editMutasi($id)
    {
        $mutasiRequest = MutasiRequest::with(['user', 'user.profile'])
            ->findOrFail($id);

        return view('kepalapusat.mutasi.edit', compact('mutasiRequest'));
    }

    public function approveMutasi($id)
    {
        $mutasiRequest = MutasiRequest::findOrFail($id);

        $oreste = PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$oreste || ($oreste->ranking_kinerja === null && $oreste->ranking_kompetensi === null)) {
            return back()->with('error', 'Ranking kinerja/kompetensi belum tersedia. Tidak bisa approve.');
        }

        $mutasiRequest->update([
            'status' => 'diterima',
            'keputusan_akhir' => 'diterima',
            'tanggal_keputusan' => now(),
            'keterangan' => 'Permohonan mutasi disetujui oleh Kepala Pusat'
        ]);

        HasilAkhir::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->update([
                'status' => 'diterima'
            ]);

        return redirect()->route('kepalapusat.mutasi.index')->with('success', 'Permohonan mutasi berhasil disetujui!');
    }

    public function rejectMutasi($id)
    {
        $mutasiRequest = MutasiRequest::findOrFail($id);

        $oreste = PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$oreste || ($oreste->ranking_kinerja === null && $oreste->ranking_kompetensi === null)) {
            return back()->with('error', 'Ranking kinerja/kompetensi belum tersedia. Tidak bisa reject.');
        }

        $mutasiRequest->update([
            'status' => 'ditolak',
            'keputusan_akhir' => 'ditolak',
            'tanggal_keputusan' => now(),
            'keterangan' => 'Permohonan mutasi ditolak oleh Kepala Pusat'
        ]);

        HasilAkhir::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->update([
                'status' => 'ditolak'
            ]);

        return redirect()->route('kepalapusat.mutasi.index')->with('success', 'Permohonan mutasi berhasil ditolak!');
    }

    public function exportMutasi()
    {
        $mutasiRequests = MutasiRequest::with(['user', 'user.profile'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($mutasiRequests);
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $filename = 'permohonan_mutasi_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new MutasiRequestExport($search), $filename);
    }

    public function daftarPermohonan()
    {
        return MutasiRequest::with('user')->get();
    }

    public function keputusanAkhir(Request $r, $id)
    {
        $mutasi = MutasiRequest::findOrFail($id);

        if (!in_array($r->status, ['diterima', 'ditolak'])) {
            return back()->with('error', 'Status tidak valid.');
        }
        if (!in_array($r->keputusan_akhir, ['diterima', 'ditolak'])) {
            return back()->with('error', 'Keputusan akhir tidak valid.');
        }

        $mutasi->update([
            'status' => $r->status,
            'tanggal_keputusan' => now(),
            'keputusan_akhir' => $r->keputusan_akhir,
            'keterangan' => $r->keterangan,
        ]);
        return redirect()->route('kepalapusat.mutasi.index')->with('success', 'Keputusan akhir berhasil disimpan.');
    }

    public function dataPegawai()
    {
        return User::with('profile')->whereHas('role', fn ($q) => $q->where('name', 'pegawai'))->get();
    }

}
