<?php

namespace App\Http\Controllers;

use App\Models\HasilAkhir;
use Illuminate\Http\Request;
use App\Models\MutasiRequest;
use App\Models\User;
use App\Models\PegawaiProfile;
use App\Models\Penilaian;
use App\Models\PerhitunganOreste;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{

    public function indexMutasi()
    {
        $user = auth()->user();
        $mutasiRequests = MutasiRequest::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return view('pegawai.mutasi.index', compact('mutasiRequests'));
    }

    public function createMutasi()
    {
        $user = auth()->user();
        return view('pegawai.mutasi.create', compact('user'));
    }

    public function storeMutasi(Request $request)
    {
        $user = auth()->user();

        if ($user->masa_kerja < 60) {
            return redirect()->back()->with('error', 'Masa kerja minimal 5 tahun untuk mengajukan mutasi');
        }

        $request->validate([
            'pendidikan_terakhir' => 'required|string|max:255',
            'alasan_mutasi' => 'required|string|min:10',
            'lokasi_tujuan' => 'required|string|max:255',
        ]);

        MutasiRequest::create([
            'user_id' => $user->id,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'alasan_mutasi' => $request->alasan_mutasi,
            'lokasi_tujuan' => $request->lokasi_tujuan,
            'status' => 'menunggu',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('pegawai.mutasi.index')->with('success', 'Permohonan mutasi berhasil diajukan!');
    }

    public function showMutasi($id)
    {
        $user = auth()->user();
        $mutasiRequest = MutasiRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('pegawai.mutasi.show', compact('mutasiRequest'));
    }

    public function editMutasi($id)
    {
        $user = auth()->user();
        $mutasiRequest = MutasiRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        return view('pegawai.mutasi.edit', compact('mutasiRequest'));
    }

    public function updateMutasi(Request $request, $id)
    {
        $user = auth()->user();
        $mutasiRequest = MutasiRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $request->validate([
            'pendidikan_terakhir' => 'required|string|max:255',
            'alasan_mutasi' => 'required|string|min:10',
            'lokasi_tujuan' => 'required|string|max:255',
        ]);

        $mutasiRequest->update([
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'alasan_mutasi' => $request->alasan_mutasi,
            'lokasi_tujuan' => $request->lokasi_tujuan,
        ]);

        return redirect()->route('pegawai.mutasi.index')->with('success', 'Permohonan mutasi berhasil diperbarui!');
    }

    public function destroyMutasi($id)
    {
        $user = auth()->user();
        $mutasiRequest = MutasiRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $mutasiRequest->delete();

        return redirect()->route('pegawai.mutasi.index')->with('success', 'Permohonan mutasi berhasil dibatalkan!');
    }

    public function ajukanMutasi(Request $r)
    {
        $user = auth()->user();
        if ($user->masa_kerja < 60) return response(['error' => 'Masa kerja kurang dari 5 tahun'], 403);
        return MutasiRequest::create([
            'user_id' => $user->id,
            'pendidikan_terakhir' => $r->pendidikan_terakhir,
            'alasan_mutasi' => $r->alasan_mutasi,
            'lokasi_tujuan' => $r->lokasi_tujuan,
            'status' => 'menunggu',
            'tanggal_pengajuan' => now(),
        ]);
    }

    public function statusMutasi()
    {
        $user = auth()->user();
        return MutasiRequest::where('user_id', $user->id)->orderByDesc('created_at')->get();
    }

    public function updateProfil(Request $r)
    {
        $user = auth()->user();
        $user->update($r->only(['name', 'pendidikan', 'jabatan', 'usia']));
        $user->profile()->update($r->only(['alamat', 'no_hp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'status_perkawinan', 'agama', 'foto']));
        return $user->load('profile');
    }

    public function penilaianPribadi()
    {
        $user = auth()->user();
        return Penilaian::where('user_id', $user->id)->with(['kriteria', 'subKriteria'])->get();
    }

    public function profil()
    {
        $user = auth()->user()->load('profile');
        return view('pegawai.profil.index', compact('user'));
    }

    private function getJabatanOptions()
    {
        return [
            'Kepala Seksi' => 'Kepala Seksi',
            'Staff' => 'Staff',
            'Supervisor' => 'Supervisor',
            'Manager' => 'Manager',
            'Direktur' => 'Direktur',
            'Administrator' => 'Administrator',
            'Analis' => 'Analis',
            'Koordinator' => 'Koordinator',
            'Kepala Divisi' => 'Kepala Divisi',
            'Kepala Departemen' => 'Kepala Departemen',
            'Asisten Manager' => 'Asisten Manager',
            'Senior Staff' => 'Senior Staff',
            'Junior Staff' => 'Junior Staff',
            'Kepala Unit' => 'Kepala Unit',
            'Kepala Sub Divisi' => 'Kepala Sub Divisi',
        ];
    }

    public function editProfil()
    {
        $user = auth()->user()->load('profile');
        $jabatanOptions = $this->getJabatanOptions();
        return view('pegawai.profil.edit', compact('user','jabatanOptions'));
    }

    public function updateProfilData(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            // 'jabatan' => 'required|string|max:255',
            'jabatan' => 'required|string|in:' . implode(',', array_keys($this->getJabatanOptions())),
            'usia' => 'required|integer|min:18|max:65',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'status_perkawinan' => 'required|string|max:50',
            'agama' => 'required|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->update($request->only(['name', 'pendidikan', 'jabatan', 'usia']));

        $profileData = $request->only([
            'alamat', 'no_hp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'status_perkawinan', 'agama'
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pegawai-fotos', 'public');
            $profileData['foto'] = $fotoPath;
            if ($user->profile && $user->profile->foto) {
                Storage::disk('public')->delete($user->profile->foto);
            }
        }

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        return redirect()->route('pegawai.profil.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function indexPenilaian() {
        $user = auth()->user();

        $penilaians = Penilaian::where('user_id', $user->id)
            ->with(['kriteria', 'subKriteria'])
            ->orderBy('tahun', 'desc')
            ->orderBy('kriteria_id')
            ->get();

            $penilaiansByYear = $penilaians->groupBy('tahun');

        $averageByYear = [];
        foreach ($penilaiansByYear as $tahun => $data) {
            $averageByYear[$tahun] = $data->avg('nilai');
        }

        return view('pegawai.penilaian.index', compact('penilaiansByYear', 'averageByYear'));
    }

    public function showPenilaian($id) {
        $user = auth()->user();

        $penilaian = Penilaian::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['kriteria', 'subKriteria'])
            ->firstOrFail();

        return view('pegawai.penilaian.show', compact('penilaian'));
    }

    public function exportPenilaianPdf()
    {
        $user = auth()->user();

        $penilaians = Penilaian::where('user_id', $user->id)
            ->with(['kriteria', 'subKriteria'])
            ->orderBy('tahun', 'desc')
            ->orderBy('kriteria_id')
            ->get();

        $penilaiansByYear = $penilaians->groupBy('tahun');
        $averageByYear = [];
        foreach ($penilaiansByYear as $tahun => $data) {
            $averageByYear[$tahun] = $data->avg('nilai');
        }

        $pdf = Pdf::loadView('pegawai.penilaian.pdf', compact('penilaiansByYear', 'averageByYear', 'user'));
        return $pdf->download('penilaian_' . $user->name . '.pdf');
    }

    public function indexHasilAkhir() {
        $user = auth()->user();

        $hasilAkhirs = HasilAkhir::where('user_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->get();

        $totalHasil = $hasilAkhirs->count();
        $nilaiTertinggi = $hasilAkhirs->max('nilai_akhir');
        $rankingTerbaik = $hasilAkhirs->min('ranking_akhir');
        $statusDiterima = $hasilAkhirs->where('status', 'diterima')->count();

        return view('pegawai.hasil-akhir.index', compact('hasilAkhirs', 'totalHasil', 'nilaiTertinggi', 'rankingTerbaik', 'statusDiterima'));
    }

    public function showHasilAkhir($id) {
        $user = auth()->user();

        $hasilAkhir = HasilAkhir::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $perhitunganOreste = PerhitunganOreste::where('user_id', $user->id)
            ->where('tahun', $hasilAkhir->tahun)
            ->first();

        $penilaians = Penilaian::where('user_id', $user->id)
            ->where('tahun', $hasilAkhir->tahun)
            ->with(['kriteria', 'subKriteria'])
            ->get();

        $penilaianKinerja = $penilaians->filter(function($penilaian) {
            return $penilaian->kriteria && $penilaian->kriteria->tipe === 'kinerja';
        });

        $penilaianKompetensi = $penilaians->filter(function($penilaian) {
            return $penilaian->kriteria && $penilaian->kriteria->tipe === 'kompetensi';
        });

        $mutasiRequest = MutasiRequest::where('user_id', $user->id)
            ->whereYear('created_at', $hasilAkhir->tahun)
            ->orderBy('created_at', 'desc')
            ->first();

        $stats = [
            'total_kriteria' => $penilaians->count(),
            'total_kinerja' => $penilaianKinerja->count(),
            'total_kompetensi' => $penilaianKompetensi->count(),
            'nilai_rata_kinerja' => $penilaianKinerja->count() > 0 ? $penilaianKinerja->avg('nilai') : 0,
            'nilai_rata_kompetensi' => $penilaianKompetensi->count() > 0 ? $penilaianKompetensi->avg('nilai') : 0,
            'nilai_tertinggi_kinerja' => $penilaianKinerja->count() > 0 ? $penilaianKinerja->max('nilai') : 0,
            'nilai_tertinggi_kompetensi' => $penilaianKompetensi->count() > 0 ? $penilaianKompetensi->max('nilai') : 0,
        ];

        return view('pegawai.hasil-akhir.show', compact(
            'hasilAkhir',
            'perhitunganOreste',
            'penilaians',
            'penilaianKinerja',
            'penilaianKompetensi',
            'mutasiRequest',
            'stats'
        ));
    }
}
