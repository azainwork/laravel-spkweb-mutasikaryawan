<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PerhitunganOreste;
use App\Models\MutasiRequest;

class MutasiController extends Controller
{

    public function prosesOreste()
    {
        $pegawais = User::whereHas('role', fn($q) => $q->where('name','pegawai'))
            ->where('masa_kerja', '>=', 60)->get();
        $tahun = date('Y');

        $kriterias = \App\Models\Kriteria::with('subKriterias')->get();

        $penilaian = \App\Models\Penilaian::whereIn('user_id', $pegawais->pluck('id'))->where('tahun', $tahun)->get();

        $bessonRanks = [];
        foreach ($kriterias as $kriteria) {
            foreach ($kriteria->subKriterias as $sub) {
                $nilai = [];
                foreach ($pegawais as $p) {
                    $n = $penilaian->where('user_id', $p->id)->where('sub_kriteria_id', $sub->id)->first();
                    $nilai[$p->id] = $n ? $n->nilai : 0;
                }

                echo "<strong>Debug Nilai Sub Kriteria: {$sub->nama} (ID: {$sub->id})</strong><br>";
                foreach ($nilai as $pid => $val) {
                    $peg = $pegawais->firstWhere('id', $pid);
                    echo "- {$peg->name} (ID: $pid): Nilai = {$val}<br>";
                }
                echo "<br>";

                arsort($nilai);
                $rank = 1;
                $prev = null;
                $same = 1;
                $ranks = [];
                foreach ($nilai as $uid => $v) {
                    if ($prev !== null && $v == $prev) {
                        $ranks[$uid] = $rank - ($same - 1) / 2;
                        $same++;
                    } else {
                        $ranks[$uid] = $rank;
                        $same = 1;
                    }
                    $prev = $v;
                    $rank++;
                }

                foreach ($ranks as $uid => $rk) {
                    $bessonRanks[$uid][$sub->id] = $rk;
                }
            }
        }


        $oresteResults = [];
        foreach ($pegawais as $p) {
            $total = 0;
            foreach ($kriterias as $kriteria) {
                $subTotal = 0;
                foreach ($kriteria->subKriterias as $sub) {
                    $br = $bessonRanks[$p->id][$sub->id] ?? 0;
                    $subTotal += $br * $sub->nilai;
                }
                $total += $subTotal * $kriteria->bobot;
            }
            $oresteResults[$p->id] = $total;
        }

        asort($oresteResults);
        $ranking = 1;
        $rankMap = [];
        foreach ($oresteResults as $uid => $val) {
            $rankMap[$uid] = $ranking;
            $ranking++;
        }

        foreach ($pegawais as $p) {
            $oreste = \App\Models\PerhitunganOreste::updateOrCreate(
                ['user_id'=>$p->id,'tahun'=>$tahun],
                [
                    'nilai_kinerja'=>$oresteResults[$p->id],
                    'ranking_kinerja'=>$rankMap[$p->id],
                    'rekomendasi_lokasi'=> $rankMap[$p->id]==1 ? 'Terdekat' : ($rankMap[$p->id]==count($pegawais)?'Luar Kota':'Sesuai Kebijakan'),
                    'status_mutasi'=>'rekomendasi',
                ]
            );
            \App\Models\HasilAkhir::updateOrCreate(
                ['user_id'=>$p->id,'tahun'=>$tahun],
                [
                    'nilai_akhir'=>$oresteResults[$p->id],
                    'ranking_akhir'=>$rankMap[$p->id],
                    'lokasi_mutasi'=>$oreste->rekomendasi_lokasi,
                    'status'=>'menunggu',
                ]
            );
        }
        return response(['message'=>'Perhitungan Oreste selesai','ranking'=>$rankMap],200);
    }

    public function rekomendasiLokasi($user_id) {
        $oreste = PerhitunganOreste::where('user_id', $user_id)->latest()->first();
        return $oreste ? $oreste->rekomendasi_lokasi : null;
    }

    public function mutasiTerdekat() {
        $tahun = date('Y');
        $top = PerhitunganOreste::where('tahun',$tahun)->orderBy('ranking_kinerja')->first();
        return $top ? $top->user : null;
    }

    public function mutasiLuarKota() {
        $tahun = date('Y');
        $last = PerhitunganOreste::where('tahun',$tahun)->orderByDesc('ranking_kinerja')->first();
        return $last ? $last->user : null;
    }
}
