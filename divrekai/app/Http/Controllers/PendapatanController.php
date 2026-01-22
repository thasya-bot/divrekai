<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendapatanController extends Controller
{
    public function index()
    {
        $unitId = auth()->user()->unit_id;

        $pendapatan = Pendapatan::where('unit_id', $unitId)
            ->orderBy('tanggal', 'desc')
            ->get();

        $hariIni = Pendapatan::where('unit_id', $unitId)
            ->whereDate('tanggal', today())
            ->sum('jumlah');

        $bulanIni = Pendapatan::where('unit_id', $unitId)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $rekapBulanan = Pendapatan::selectRaw('YEAR(tanggal) tahun, MONTH(tanggal) bulan, SUM(jumlah) total')
            ->where('unit_id', $unitId)
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('admin_unit.pendapatan.index', compact(
            'pendapatan',
            'hariIni',
            'bulanIni',
            'rekapBulanan'
        ));
    }

    public function create()
    {
        return view('admin_unit.pendapatan.input');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah'  => 'required|numeric',
        ]);

        Pendapatan::create([
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
            'unit_id'    => auth()->user()->unit_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Pendapatan berhasil ditambahkan');
    }

    public function edit(Pendapatan $pendapatan)
    {
        return view('admin_unit.pendapatan.edit', compact('pendapatan'));
    }

    public function update(Request $request, Pendapatan $pendapatan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah'  => 'required|numeric',
        ]);

        $pendapatan->update($request->only('tanggal', 'jumlah', 'keterangan'));

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Pendapatan berhasil diperbarui');
    }

    public function destroy(Pendapatan $pendapatan)
    {
        $pendapatan->delete();

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Pendapatan berhasil dihapus');
    }
}
