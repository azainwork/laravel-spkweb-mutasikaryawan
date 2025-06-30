<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;

class PenilaianController extends Controller
{
    public function index()
    {
        return Penilaian::with(['user', 'kriteria', 'subKriteria'])->get();
    }
    public function store(Request $r)
    {
        return Penilaian::create($r->all());
    }
    public function update(Request $r, $id)
    {
        $p = Penilaian::findOrFail($id);
        $p->update($r->all());
        return $p;
    }
    public function destroy($id)
    {
        return Penilaian::destroy($id);
    }
    public function penilaianPegawai($user_id)
    {
        return Penilaian::where('user_id', $user_id)->with(['kriteria', 'subKriteria'])->get();
    }
}
