<?php

namespace App\Http\Controllers;

use App\Exports\MutasiRequestExport;
use App\Exports\PerhitunganOresteExport;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\PegawaiProfile;
use App\Models\MutasiRequest;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Penilaian;
use App\Models\PerhitunganOreste;
use App\Models\HasilAkhir;
use App\Services\OresteProses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function indexPegawai(Request $request)
    {
        $pegawais = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['pegawai', 'kepala_pusat']);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $pegawais->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%')
                    ->orWhere('pendidikan', 'like', '%' . $search . '%');
            });
        }

        $pegawais = $pegawais->orderBy('name')->get();

        return view('admin.pegawai.index', compact('pegawais'));
    }


    public function createPegawai()
    {
        $roles = Role::all();
        $jabatanOptions = $this->getJabatanOptions();
        return view('admin.pegawai.form', compact('roles','jabatanOptions'));
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

    public function storePegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|unique:users|max:20',
            // 'jabatan' => 'required|string|max:100',
            'jabatan' => 'required|string|in:' . implode(',', array_keys($this->getJabatanOptions())),
            'pendidikan' => 'required|in:SMA/SMK,S1,S2,S3',
            'masa_kerja' => 'required|integer|min:0',
            'usia' => 'required|integer|min:18|max:100',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'agama' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $rolePegawai = Role::where('name', 'pegawai')->first();
            if (!$rolePegawai) {
                throw new \Exception('Role pegawai tidak ditemukan');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'pendidikan' => $request->pendidikan,
                'masa_kerja' => $request->masa_kerja,
                'usia' => $request->usia,
                'role_id' => $rolePegawai->id,
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('pegawai-fotos', 'public');
            }

            PegawaiProfile::create([
                'user_id' => $user->id,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status_perkawinan' => $request->status_perkawinan,
                'agama' => $request->agama,
                'foto' => $fotoPath,
            ]);

            DB::commit();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showPegawai($id)
    {
        $pegawai = User::with(['role', 'profile'])->findOrFail($id);
        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function editPegawai($id)
    {
        $pegawai = User::with(['role', 'profile'])->findOrFail($id);
        $jabatanOptions = $this->getJabatanOptions();
        return view('admin.pegawai.form', compact('pegawai','jabatanOptions'));
    }

    public function updatePegawai(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'nip' => 'required|string|max:20|unique:users,nip,' . $id,
            // 'jabatan' => 'required|string|max:100',
            'jabatan' => 'required|string|in:' . implode(',', array_keys($this->getJabatanOptions())),
            'pendidikan' => 'required|in:SMA/SMK,S1,S2,S3',
            'masa_kerja' => 'required|integer|min:0',
            'usia' => 'required|integer|min:18|max:100',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'agama' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'pendidikan' => $request->pendidikan,
                'masa_kerja' => $request->masa_kerja,
                'usia' => $request->usia,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $fotoPath = $user->profile->foto ?? null;
            if ($request->hasFile('foto')) {
                if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                $fotoPath = $request->file('foto')->store('pegawai-fotos', 'public');
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'status_perkawinan' => $request->status_perkawinan,
                    'agama' => $request->agama,
                    'foto' => $fotoPath,
                ]
            );

            DB::commit();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyPegawai($id)
    {
        try {
            $user = User::findOrFail($id);

            $hasMutasiRequests = $user->mutasiRequests()->exists();
            $hasPenilaian = $user->penilaians()->exists();
            $hasHasilAkhir = $user->hasilAkhirs()->exists();

            if ($hasMutasiRequests || $hasPenilaian || $hasHasilAkhir) {
                return back()->with('error', 'Pegawai tidak dapat dihapus karena memiliki data terkait (mutasi, penilaian, atau hasil akhir)');
            }

            if ($user->profile && $user->profile->foto) {
                if (Storage::disk('public')->exists($user->profile->foto)) {
                    Storage::disk('public')->delete($user->profile->foto);
                }
            }

            if ($user->profile) {
                $user->profile->delete();
            }

            $user->delete();

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Kriteria
    public function indexKriteria()
    {
        $kriteria = Kriteria::with('subKriterias')->orderBy('nama')->get();
        return view('admin.kriteria.index', compact('kriteria'));
    }

    public function showKriteria($id)
    {
        $kriteria = Kriteria::with('subKriterias')->findOrFail($id);
        return view('admin.kriteria.show', compact('kriteria'));
    }


    public function createKriteria()
    {
        return view('admin.kriteria.form');
    }

    public function storeKriteria(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kriteria,nama' . (isset($kriteria) ? ',' . $kriteria->id : ''),
            'tipe' => 'required|in:kinerja,kompetensi',
            'bobot' => 'required|numeric|min:0|max:1',
            'sub_kriteria_nama' => 'nullable|array',
            'sub_kriteria_nama.*' => 'required|string|max:100',
            'sub_kriteria_nilai_min' => 'nullable|array',
            'sub_kriteria_nilai_min.*' => 'required|numeric',
            'sub_kriteria_nilai_max' => 'nullable|array',
            'sub_kriteria_nilai_max.*' => 'required|numeric|gte:sub_kriteria_nilai_min.*',
            'sub_kriteria_bobot' => 'nullable|array',
            'sub_kriteria_bobot.*' => 'nullable|numeric|min:0|max:1',
        ]);

        try {
            DB::beginTransaction();

            $kriteria = Kriteria::create([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'bobot' => $request->bobot,
            ]);

            if ($request->has('sub_kriteria_nama') && is_array($request->sub_kriteria_nama)) {
                foreach ($request->sub_kriteria_nama as $index => $nama) {
                    SubKriteria::create([
                        'kriteria_id' => $kriteria->id,
                        'nama' => $nama,
                        'nilai_min' => $request->sub_kriteria_nilai_min[$index] ?? null,
                        'nilai_max' => $request->sub_kriteria_nilai_max[$index] ?? null,
                        'bobot'     => $request->sub_kriteria_bobot[$index] ?? 1,
                        'urutan'    => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editKriteria($id)
    {
        $kriteria = Kriteria::with('subKriterias')->findOrFail($id);
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function updateKriteria(Request $request, $id)
    {

        $kriteria = Kriteria::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100|unique:kriteria,nama' . (isset($kriteria) ? ',' . $kriteria->id : ''),
            'tipe' => 'required|in:kinerja,kompetensi',
            'bobot' => 'required|numeric|min:0|max:1',
            'sub_kriteria_nama' => 'nullable|array',
            'sub_kriteria_nama.*' => 'required|string|max:100',
            'sub_kriteria_nilai_min' => 'nullable|array',
            'sub_kriteria_nilai_min.*' => 'required|numeric',
            'sub_kriteria_nilai_max' => 'nullable|array',
            'sub_kriteria_nilai_max.*' => 'required|numeric|gte:sub_kriteria_nilai_min.*',
            'sub_kriteria_bobot' => 'nullable|array',
            'sub_kriteria_bobot.*' => 'nullable|numeric|min:0|max:1',
        ]);


        try {
            DB::beginTransaction();

            $kriteria->update([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
                'bobot' => $request->bobot,
            ]);

            $kriteria->subKriterias()->delete();

            if ($request->has('sub_kriteria_nama') && is_array($request->sub_kriteria_nama)) {
                foreach ($request->sub_kriteria_nama as $index => $nama) {
                    SubKriteria::create([
                        'kriteria_id' => $kriteria->id,
                        'nama' => $nama,
                        'nilai_min' => $request->sub_kriteria_nilai_min[$index] ?? null,
                        'nilai_max' => $request->sub_kriteria_nilai_max[$index] ?? null,
                        'bobot'     => $request->sub_kriteria_bobot[$index] ?? 1,
                        'urutan'    => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroyKriteria($id)
    {
        try {
            $kriteria = Kriteria::findOrFail($id);

            $hasPenilaian = $kriteria->penilaians()->exists();
            $hasSubKriteria = $kriteria->subKriterias()->exists();

            if ($hasPenilaian) {
                return back()->with('error', 'Kriteria tidak dapat dihapus karena memiliki data penilaian terkait');
            }

            if ($hasSubKriteria) {
                $kriteria->subKriterias()->delete();
            }

            $kriteria->delete();

            return redirect()->route('admin.kriteria.index')
                ->with('success', 'Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getSubKriteria($id)
    {
        $kriteria = Kriteria::with('subKriterias')->findOrFail($id);
        return response()->json($kriteria->subKriterias);
    }

    public function validateKriteriaWeights()
    {
        $totalKinerja = \App\Models\Kriteria::where('tipe', 'kinerja')->sum('bobot');
        $totalKompetensi = \App\Models\Kriteria::where('tipe', 'kompetensi')->sum('bobot');

        $isValidKinerja = abs($totalKinerja - 1.0) < 0.01;
        $isValidKompetensi = abs($totalKompetensi - 1.0) < 0.01;

        return response()->json([
            'total_kinerja' => $totalKinerja,
            'is_valid_kinerja' => $isValidKinerja,
            'total_kompetensi' => $totalKompetensi,
            'is_valid_kompetensi' => $isValidKompetensi,
            'message_kinerja' => $isValidKinerja
                ? 'Total bobot kriteria Kinerja valid (1.0)'
                : 'Total bobot kriteria Kinerja harus 1.0, saat ini: ' . $totalKinerja,
            'message_kompetensi' => $isValidKompetensi
                ? 'Total bobot kriteria Kompetensi valid (1.0)'
                : 'Total bobot kriteria Kompetensi harus 1.0, saat ini: ' . $totalKompetensi,
        ]);
    }

    // Penilaian
    public function indexPenilaian()
    {
        $penilaian = Penilaian::with(['user', 'kriteria', 'subKriteria'])
            ->orderBy('tahun', 'desc')
            ->orderBy('user_id')
            ->get();

        $penilaianByTahun = $penilaian->groupBy('tahun');

        $tahunList = Penilaian::distinct()->pluck('tahun')->sort()->reverse();

        $pegawaiEligible = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->where('masa_kerja', '>=', 60)
            ->orderBy('name')
            ->get();

        return view('admin.penilaian.index', compact('penilaianByTahun', 'tahunList', 'pegawaiEligible'));
    }

    public function createPenilaian()
    {
        $pegawai = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->where('masa_kerja', '>=', 1)
            ->orderBy('name')
            ->get();

        $kriteria = Kriteria::with('subKriterias')->get();

        $tahun = request('tahun', date('Y'));

        return view('admin.penilaian.form', compact('pegawai', 'kriteria', 'tahun'));
    }

    public function storePenilaian(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kriteria_id' => 'required|exists:kriteria,id',
            'sub_kriteria_id' => 'required|exists:sub_kriteria,id',
            'nilai' => 'required|numeric',
            'tahun' => 'required|digits:4',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $existing = Penilaian::where('user_id', $request->user_id)
                ->where('kriteria_id', $request->kriteria_id)
                ->where('tahun', $request->tahun)
                ->first();

            if ($existing) {
                return back()->withInput()->with('error', 'Penilaian untuk pegawai & kriteria di tahun ini sudah ada.');
            }

            $subKriteria = SubKriteria::find($request->sub_kriteria_id);
            if (!$subKriteria) {
                return back()->withInput()->with('error', 'Sub Kriteria tidak ditemukan.');
            }

            if ($request->nilai < $subKriteria->nilai_min || $request->nilai > $subKriteria->nilai_max) {
                return back()->withInput()->with('error', 'Nilai harus di antara ' . $subKriteria->nilai_min . ' dan ' . $subKriteria->nilai_max . ' untuk subkriteria ' . $subKriteria->nama);
            }

            Penilaian::create([
                'user_id' => $request->user_id,
                'kriteria_id' => $request->kriteria_id,
                'sub_kriteria_id' => $request->sub_kriteria_id,
                'nilai' => $request->nilai,
                'tahun' => $request->tahun,
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('admin.penilaian.index')
                ->with('success', 'Data penilaian berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getPegawaiDetail($userId)
    {
        $user = User::with('profile')->find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'nip' => $user->nip,
            'jabatan' => $user->jabatan,
            'pendidikan' => $user->pendidikan,
            'masa_kerja' => $user->masa_kerja . ' bulan',
            'usia' => $user->usia . ' tahun',

            'no_hp' => $user->profile?->no_hp ?? '-',
            'alamat' => $user->profile?->alamat ?? 'Belum diisi',
            'status_perkawinan' => $user->profile?->status_perkawinan ?? '-',
        ]);
    }


    public function getSubKriteriaByKriteria($kriteriaId)
    {
        $subKriterias = SubKriteria::where('kriteria_id', $kriteriaId)->get();
        return response()->json($subKriterias);
    }

    public function showPenilaian($id)
    {
        $penilaian = Penilaian::with(['user', 'kriteria', 'subKriteria'])->findOrFail($id);
        return view('admin.penilaian.show', compact('penilaian'));
    }

    public function editPenilaian($id)
    {
        $penilaian = Penilaian::with(['user', 'kriteria', 'subKriteria'])->findOrFail($id);

        $pegawai = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->orderBy('name')
            ->get();

        $kriteria = Kriteria::with('subKriterias')->get();

        return view('admin.penilaian.edit', compact('penilaian', 'pegawai', 'kriteria'));
    }

    public function updatePenilaian(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kriteria_id' => 'required|exists:kriteria,id',
            'sub_kriteria_id' => 'required|exists:sub_kriteria,id',
            'nilai' => 'required|numeric',
            'tahun' => 'required|digits:4',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            $existing = Penilaian::where('user_id', $request->user_id)
                ->where('kriteria_id', $request->kriteria_id)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $penilaian->id)
                ->first();

            if ($existing) {
                return back()->withInput()->with('error', 'Penilaian untuk pegawai & kriteria di tahun ini sudah ada.');
            }

            $subKriteria = SubKriteria::find($request->sub_kriteria_id);
            if (!$subKriteria) {
                return back()->withInput()->with('error', 'Sub Kriteria tidak ditemukan.');
            }

            if ($request->nilai < $subKriteria->nilai_min || $request->nilai > $subKriteria->nilai_max) {
                return back()->withInput()->with('error', 'Nilai harus di antara ' . $subKriteria->nilai_min . ' dan ' . $subKriteria->nilai_max . ' untuk subkriteria ' . $subKriteria->nama);
            }

            $penilaian->update([
                'user_id' => $request->user_id,
                'kriteria_id' => $request->kriteria_id,
                'sub_kriteria_id' => $request->sub_kriteria_id,
                'nilai' => $request->nilai,
                'tahun' => $request->tahun,
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('admin.penilaian.index')
                ->with('success', 'Data penilaian berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroyPenilaian($id)
    {
        try {
            $penilaian = Penilaian::findOrFail($id);
            $penilaian->delete();

            return redirect()->route('admin.penilaian.index')
                ->with('success', 'Data penilaian berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkCreatePenilaian()
    {
        $pegawai = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->orderBy('name')
            ->get();

        $kriteria = Kriteria::with('subKriterias')->get();

        $tahun = request('tahun', date('Y'));

        return view('admin.penilaian.bulk-create', compact('pegawai', 'kriteria', 'tahun'));
    }

    public function storeBulkPenilaian(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'penilaian' => 'required|array',
            'penilaian.*.user_id' => 'required|exists:users,id',
            'penilaian.*.kriteria_id' => 'required|exists:kriteria,id',
            'penilaian.*.sub_kriteria_id' => 'required|exists:sub_kriteria,id',
            'penilaian.*.nilai' => 'required|numeric',
            'penilaian.*.catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errorCount = 0;
            $errorMsg = [];

            foreach ($request->penilaian as $index => $data) {
                $subKriteria = \App\Models\SubKriteria::find($data['sub_kriteria_id']);
                if (!$subKriteria) {
                    $errorCount++;
                    $errorMsg[] = "Sub Kriteria tidak ditemukan pada baris ke-" . ($index + 1);
                    continue;
                }
                if ($data['nilai'] < $subKriteria->nilai_min || $data['nilai'] > $subKriteria->nilai_max) {
                    $errorCount++;
                    $errorMsg[] = "Nilai pada baris ke-" . ($index + 1) . " harus di antara {$subKriteria->nilai_min} dan {$subKriteria->nilai_max}";
                    continue;
                }

                $existingPenilaian = \App\Models\Penilaian::where([
                    'user_id' => $data['user_id'],
                    'kriteria_id' => $data['kriteria_id'],
                    'sub_kriteria_id' => $data['sub_kriteria_id'],
                    'tahun' => $request->tahun,
                ])->first();

                if (!$existingPenilaian) {
                    \App\Models\Penilaian::create([
                        'user_id' => $data['user_id'],
                        'kriteria_id' => $data['kriteria_id'],
                        'sub_kriteria_id' => $data['sub_kriteria_id'],
                        'nilai' => $data['nilai'],
                        'tahun' => $request->tahun,
                        'catatan' => $data['catatan'] ?? null,
                    ]);
                    $successCount++;
                } else {
                    $errorCount++;
                    $errorMsg[] = "Data penilaian sudah ada pada baris ke-" . ($index + 1);
                }
            }

            DB::commit();

            $message = "Berhasil menambahkan {$successCount} data penilaian";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} data gagal: " . implode('; ', $errorMsg);
            }

            return redirect()->route('admin.penilaian.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function validateCompletenessPenilaian(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $kriteriaCount = Kriteria::count();

        $pegawai = User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
            ->whereHas('penilaians', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->orderBy('name')
            ->get();

        $result = [];

        foreach ($pegawai as $user) {
            $penilaianCount = Penilaian::where('user_id', $user->id)
                ->where('tahun', $tahun)
                ->distinct('kriteria_id')
                ->count('kriteria_id');

            $percentage = $kriteriaCount > 0 ? round($penilaianCount / $kriteriaCount * 100, 1) : 0;

            $result[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'penilaian_count' => $penilaianCount,
                'total_kriteria' => $kriteriaCount,
                'percentage' => $percentage,
                'is_complete' => $penilaianCount == $kriteriaCount,
            ];
        }

        return response()->json($result);
    }

    public function indexPerhitungan()
    {
        $tahun = request('tahun', date('Y'));

        $perhitunganOreste = PerhitunganOreste::with('user')
            ->where('tahun', $tahun)
            ->get();

        $perhitunganKinerja = $perhitunganOreste->sortBy('ranking_kinerja')->values();
        $perhitunganKompetensi = $perhitunganOreste->sortBy('ranking_kompetensi')->values();

        $stats = [
            'total_pegawai' => User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))->count(),
            'eligible_mutasi' => User::whereHas('role', fn ($q) => $q->where('name', 'pegawai'))
                ->where('masa_kerja', '>=', 60)->count(),
            'sudah_dihitung' => $perhitunganOreste->count(),
            'tahun_aktif' => $tahun,
        ];

        $tahunList = PerhitunganOreste::distinct()->pluck('tahun')->sort()->reverse();

        return view('admin.perhitungan.index', compact(
            'perhitunganKinerja', 'perhitunganKompetensi', 'stats', 'tahunList', 'tahun'
        ));
    }

    public function showPerhitungan($id)
    {
        $perhitunganOreste = PerhitunganOreste::with('user')->findOrFail($id);

        $penilaians = Penilaian::where('user_id', $perhitunganOreste->user_id)
            // ->where('year', $perhitunganOreste->year)
            ->with(['kriteria', 'subKriteria'])
            ->get();

        $penilaianKinerja = $penilaians->filter(function($penilaian) {
            return $penilaian->kriteria && $penilaian->kriteria->tipe === 'kinerja';
        });

        $penilaianKompetensi = $penilaians->filter(function($penilaian) {
            return $penilaian->kriteria && $penilaian->kriteria->tipe === 'kompetensi';
        });

        $mutasiRequest = MutasiRequest::where('user_id', $perhitunganOreste->user_id)
            ->whereYear('created_at', $perhitunganOreste->tahun)
            ->orderBy('created_at', 'desc')
            ->first();

        $hasilAkhir = HasilAkhir::where('user_id', $perhitunganOreste->user_id)
            ->where('tahun', $perhitunganOreste->tahun)
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

        return view('admin.perhitungan.show', compact(
            'perhitunganOreste',
            'penilaians',
            'penilaianKinerja',
            'penilaianKompetensi',
            'mutasiRequest',
            'hasilAkhir',
            'stats'
        ));
    }

    public function prosesPerhitungan(Request $request)
    {
        try {
            OresteProses::prosesSemuaTipe();
            return redirect()->route('admin.perhitungan.index')
                ->with('success', 'Perhitungan Oreste berhasil diproses untuk Kinerja & Kompetensi!');
        } catch (\Exception $e) {
            return redirect()->route('admin.perhitungan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetPerhitungan()
    {
        try {
            $tahun = request('tahun', date('Y'));

            PerhitunganOreste::where('tahun', $tahun)->delete();
            HasilAkhir::where('tahun', $tahun)->delete();

            return redirect()->route('admin.perhitungan.index')
                ->with('success', 'Data perhitungan tahun ' . $tahun . ' berhasil direset!');
        } catch (\Exception $e) {
            return redirect()->route('admin.perhitungan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPerhitungan()
    {
        $tahun = request('tahun', date('Y'));

        $perhitungan = PerhitunganOreste::with('user')
            ->where('tahun', $tahun)
            ->orderBy('ranking_kinerja')
            ->get();

        $exportData = [];

        foreach ($perhitungan as $index => $item) {
            $exportData[] = [
                'No' => $index + 1,
                'Nama Pegawai' => $item->user->name,
                'NIP' => $item->user->nip,
                'Jabatan' => $item->user->jabatan,
                'Pendidikan' => $item->user->pendidikan,
                'Masa Kerja (Bulan)' => $item->user->masa_kerja,
                'Usia' => $item->user->usia,
                'Nilai Kinerja' => number_format($item->nilai_kinerja ?? 0, 3),
                'Ranking Kinerja' => $item->ranking_kinerja ?? '-',
                'Nilai Kompetensi' => number_format($item->nilai_kompetensi ?? 0, 3),
                'Ranking Kompetensi' => $item->ranking_kompetensi ?? '-',
                'Rekomendasi Lokasi' => $item->rekomendasi_lokasi ?? '-',
                'Status Mutasi' => ucfirst($item->status_mutasi ?? 'belum'),
                'Tahun' => $item->tahun,
            ];
        }

        $filename = 'perhitungan_oreste_' . $tahun . '_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new PerhitunganOresteExport($exportData), $filename);
    }

    public function indexHasilAkhir()
    {
        $hasilAkhir = HasilAkhir::with('user')->orderBy('ranking_akhir')->get();
        return view('admin.hasil-akhir.index', compact('hasilAkhir'));
    }

    public function showHasilAkhir($id)
    {
        $hasilAkhir = HasilAkhir::with('user')->findOrFail($id);
        return view('admin.hasil-akhir.show', compact('hasilAkhir'));
    }

    public function lihatPermohonanMutasi(Request $request)
    {
        $query = MutasiRequest::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('jabatan', 'like', '%' . $search . '%');
            })->orWhere('lokasi_tujuan', 'like', '%' . $search . '%')
            ->orWhere('pendidikan_terakhir', 'like', '%' . $search . '%')
            ->orWhere('alasan_mutasi', 'like', '%' . $search . '%');
        }

        $mutasi = $query->orderBy('created_at', 'desc')->get();

        return view('admin.mutasi.index', compact('mutasi'));
    }

    public function showMutasi($id)
    {
        $mutasiRequest = MutasiRequest::with(['user', 'user.profile'])
            ->findOrFail($id);

        $penilaians = Penilaian::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->with(['kriteria', 'subKriteria'])
            ->get();

        $perhitunganOreste = PerhitunganOreste::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        $hasilAkhir = HasilAkhir::where('user_id', $mutasiRequest->user_id)
            ->where('tahun', date('Y'))
            ->first();

        $totalKriteria = $penilaians->count();
        $nilaiRataRata = $penilaians->avg('nilai');
        $nilaiTertinggi = $penilaians->max('nilai');
        $nilaiTerendah = $penilaians->min('nilai');

        return view('admin.mutasi.show', compact(
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
    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $filename = 'permohonan_mutasi_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new MutasiRequestExport($search), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = MutasiRequest::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('jabatan', 'like', '%' . $search . '%');
            })->orWhere('lokasi_tujuan', 'like', '%' . $search . '%')
            ->orWhere('pendidikan_terakhir', 'like', '%' . $search . '%');
        }

        $mutasi = $query->orderBy('created_at', 'desc')->get();
        $search = $request->get('search');

        $pdf = Pdf::loadView('admin.mutasi.pdf', compact('mutasi', 'search'));
        $filename = 'permohonan_mutasi_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
}
