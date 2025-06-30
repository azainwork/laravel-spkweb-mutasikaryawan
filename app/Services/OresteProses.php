<?php

namespace App\Services;

use App\Models\User;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PerhitunganOreste;
use App\Models\HasilAkhir;

class OresteProses
{
    /**
     * Proses perhitungan ORESTE untuk semua pegawai eligible
     * @param string $tipe 'kinerja' atau 'kompetensi'
     * @param int|null $tahun
     * @return array
     */
    public static function proses($tipe = 'kinerja', $tahun = null)
    {
        $tahun = $tahun ?? date('Y');

        // 1. Ambil kriteria dan subkriteria sesuai tipe
        $kriterias = Kriteria::with('subKriterias')
            ->where('tipe', $tipe)
            ->get();
        $kriteriaIds = $kriterias->pluck('id')->toArray();

        // 2. Ambil pegawai eligible yang sudah punya penilaian lengkap untuk semua kriteria di tahun tsb
        $pegawaiIds = Penilaian::where('tahun', $tahun)
            ->whereIn('kriteria_id', $kriteriaIds)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(DISTINCT kriteria_id) = ?', [count($kriteriaIds)])
            ->pluck('user_id')
            ->toArray();

        $pegawais = User::whereHas('role', fn($q) => $q->where('name','pegawai'))
            ->where('masa_kerja', '>=', 60)
            ->whereIn('id', $pegawaiIds)
            ->get();

        // 3. Ambil penilaian
        $penilaian = Penilaian::whereIn('user_id', $pegawais->pluck('id'))
            ->where('tahun', $tahun)
            ->get();

        // 4. Hitung Bessonrank
        $bessonRanks = [];
        foreach ($kriterias as $kriteria) {
            $nilaiKriteria = [];
            foreach ($pegawais as $p) {
                $totalNilai = 0;
                foreach ($kriteria->subKriterias as $sub) {
                    $n = $penilaian->where('user_id', $p->id)->where('sub_kriteria_id', $sub->id)->first();
                    $totalNilai += $n ? $n->nilai : 0;
                }
                $nilaiKriteria[$p->id] = $totalNilai;
            }
            $ranks = self::bessonRank($nilaiKriteria);
            foreach ($ranks as $uid => $rk) {
                $bessonRanks[$uid][$kriteria->id] = $rk;
            }
        }

        // 5. Hitung Distance Score
        $distanceScores = [];
        $R = count($kriterias); // jumlah kriteria
        foreach ($kriterias as $idx => $kriteria) {
            $j = $idx + 1; // nomor urut kriteria, mulai dari 1
            foreach ($pegawais as $p) {
                $br = $bessonRanks[$p->id][$kriteria->id];
                $term1 = 0.5 * pow($br, $R);
                $term2 = 0.5 * pow($j, $R);
                $base = $term1 + $term2;
                $score = pow($base, 1 / $R); // akar pangkat R
                $distanceScores[$p->id][$kriteria->id] = $score;
            }
        }

        // 6. Hitung Nilai Preferensi
        $preferensi = [];
        foreach ($pegawais as $p) {
            $total = 0;
            foreach ($kriterias as $kriteria) {
                $score = $distanceScores[$p->id][$kriteria->id];
                $total += $score * $kriteria->bobot;
            }
            $preferensi[$p->id] = $total;
        }


        // 7. Ranking Akhir (nilai KECIL = ranking 1, ini BENAR untuk ORESTE)
        asort($preferensi);
        $ranking = 1;
        $rankMap = [];
        foreach ($preferensi as $uid => $val) {
            $rankMap[$uid] = $ranking;
            $ranking++;
        }

        // 8. Simpan ke tabel perhitungan_oreste
        foreach ($pegawais as $p) {
            PerhitunganOreste::updateOrCreate(
                ['user_id'=>$p->id,'tahun'=>$tahun],
                [
                    $tipe === 'kinerja' ? 'nilai_kinerja' : 'nilai_kompetensi' => $preferensi[$p->id],
                    $tipe === 'kinerja' ? 'ranking_kinerja' : 'ranking_kompetensi' => $rankMap[$p->id],
                    'rekomendasi_lokasi'=> $rankMap[$p->id]==1 ? 'Terdekat' : ($rankMap[$p->id]==count($pegawais)?'Luar Kota':'Sesuai Kebijakan'),
                    'status_mutasi'=>'rekomendasi',
                ]
            );
        }

        return [
            'preferensi' => $preferensi,
            'ranking' => $rankMap,
        ];
    }

    /**
     * Proses ORESTE untuk semua tipe (kinerja & kompetensi) dan buat hasil akhir
     */
    public static function prosesSemuaTipe($tahun = null)
    {
        $tahun = $tahun ?? date('Y');

        $hasilKinerja = self::proses('kinerja', $tahun);
        $hasilKompetensi = self::proses('kompetensi', $tahun);

        self::buatHasilAkhir($tahun);

        return [
            'kinerja' => $hasilKinerja,
            'kompetensi' => $hasilKompetensi
        ];
    }

    /**
     * Buat hasil akhir berdasarkan gabungan kinerja dan kompetensi
     */
    public static function buatHasilAkhir($tahun)
    {
        $perhitungan = PerhitunganOreste::where('tahun', $tahun)->get();

        $nilaiAkhir = [];
        foreach ($perhitungan as $p) {
            $nilaiKinerja = $p->nilai_kinerja ?? 0;
            $nilaiKompetensi = $p->nilai_kompetensi ?? 0;

            if ($nilaiKinerja == 0 && $nilaiKompetensi > 0) {
                $nilaiAkhir[$p->user_id] = $nilaiKompetensi;
            } elseif ($nilaiKompetensi == 0 && $nilaiKinerja > 0) {
                $nilaiAkhir[$p->user_id] = $nilaiKinerja;
            } else {
                $nilaiAkhir[$p->user_id] = ($nilaiKinerja + $nilaiKompetensi) / 2;
            }
        }

        asort($nilaiAkhir);
        $ranking = 1;
        $rankMap = [];
        foreach ($nilaiAkhir as $uid => $val) {
            $rankMap[$uid] = $ranking;
            $ranking++;
        }

        foreach ($perhitungan as $p) {
            $nilaiAkhirPegawai = $nilaiAkhir[$p->user_id] ?? 0;
            $rankingAkhirPegawai = $rankMap[$p->user_id] ?? 0;

            $lokasiMutasi = $rankingAkhirPegawai == 1 ? 'Terdekat' :
                           ($rankingAkhirPegawai == count($perhitungan) ? 'Luar Kota' : 'Sesuai Kebijakan');

            HasilAkhir::updateOrCreate(
                ['user_id'=>$p->user_id,'tahun'=>$tahun],
                [
                    'nilai_akhir' => $nilaiAkhirPegawai,
                    'ranking_akhir' => $rankingAkhirPegawai,
                    'lokasi_mutasi' => $lokasiMutasi,
                    'status' => 'menunggu',
                ]
            );
        }
    }

    /**
     * Hitung Bessonrank (ranking per subkriteria)
     * @param array $nilaiArray [user_id => nilai]
     * @return array [user_id => bessonrank]
     */
    public static function bessonRank($nilaiArray)
    {
        arsort($nilaiArray);
        $ranks = [];
        $values = array_values($nilaiArray);
        $keys = array_keys($nilaiArray);

        $i = 0;
        $currentRank = 1;
        while ($i < count($values)) {
            $sameValueKeys = [$keys[$i]];
            $sameValue = $values[$i];
            $j = $i + 1;
            while ($j < count($values) && $values[$j] == $sameValue) {
                $sameValueKeys[] = $keys[$j];
                $j++;
            }
            $rankStart = $currentRank;
            $rankEnd = $currentRank + count($sameValueKeys) - 1;
            $avgRank = ($rankStart + $rankEnd) / 2;
            foreach ($sameValueKeys as $k) {
                $ranks[$k] = $avgRank;
            }
            $currentRank += count($sameValueKeys);
            $i = $j;
        }
        return $ranks;
    }
}
