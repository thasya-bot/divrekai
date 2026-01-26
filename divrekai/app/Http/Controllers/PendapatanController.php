<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendapatanController extends Controller
{
public function index(Request $request)
{
    $unitId = auth()->user()->unit_id;

    /*
    |==============================
    | HARIAN
    |==============================
    */
    $query = Pendapatan::where('unit_id', $unitId);
    $limitHarian = $request->get('limit_harian', 4);

    // ❗ PENTING: JANGAN BACA FILTER BULANAN
    if ($request->filled('tanggal') && !$request->filled('bulan')) {
        $query->whereDate('tanggal', $request->tanggal);
    }

    if ($request->filled('search') && !$request->filled('search_bulanan')) {
        $query->where('keterangan', 'like', '%' . $request->search . '%');
    }

    $pendapatan = $query
        ->orderBy('tanggal', 'desc')
        ->paginate($limitHarian, ['*'], 'page_harian');


    /*
    |==============================
    | RINGKASAN
    |==============================
    */
    $hariIni = Pendapatan::where('unit_id', $unitId)
        ->whereDate('tanggal', today())
        ->sum('jumlah');

    $bulanIni = Pendapatan::where('unit_id', $unitId)
        ->whereMonth('tanggal', now()->month)
        ->whereYear('tanggal', now()->year)
        ->sum('jumlah');

    /*
    |==============================
    | BULANAN
    |==============================
    */
    $limitBulanan = $request->get('limit_bulanan', 4);
    $rekapQuery = Pendapatan::selectRaw('
            YEAR(tanggal) as tahun,
            MONTH(tanggal) as bulan,
            SUM(jumlah) as total
        ')
        ->where('unit_id', $unitId)
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc');

    if ($request->filled('bulan')) {
        [$tahun, $bulan] = explode('-', $request->bulan);
        $rekapQuery->whereYear('tanggal', $tahun)
                   ->whereMonth('tanggal', $bulan);
    }

    if ($request->filled('search_bulanan')) {
        $rekapQuery->havingRaw(
            "CONCAT(tahun, '-', LPAD(bulan,2,'0')) LIKE ?",
            ['%' . $request->search_bulanan . '%']
        );
    }

    $rekapBulanan = $rekapQuery
        ->paginate($limitBulanan, ['*'], 'page_bulanan')
        ->withQueryString();

    return view('admin_unit.pendapatan.index', compact(
        'pendapatan',
        'hariIni',
        'bulanIni',
        'rekapBulanan'
    ));
}
    public function create()
    {
        $totalHariIni = Pendapatan::where('unit_id', auth()->user()->unit_id)
            ->whereDate('tanggal', today())
            ->sum('jumlah');

        return view('admin_unit.pendapatan.input', compact('totalHariIni'));
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
public function bulanan(Request $request)
{
    $unitId = auth()->user()->unit_id;

    $limitBulanan = $request->get('limit_bulanan', 4);

    $rekapQuery = Pendapatan::selectRaw('
            YEAR(tanggal) as tahun,
            MONTH(tanggal) as bulan,
            SUM(jumlah) as total
        ')
        ->where('unit_id', $unitId)
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc');

    if ($request->filled('bulan')) {
        [$tahun, $bulan] = explode('-', $request->bulan);
        $rekapQuery->whereYear('tanggal', $tahun)
                   ->whereMonth('tanggal', $bulan);
    }

    if ($request->filled('search_bulanan')) {
        $rekapQuery->havingRaw(
            "CONCAT(tahun,'-',LPAD(bulan,2,'0')) LIKE ?",
            ['%' . $request->search_bulanan . '%']
        );
    }
}
    public function destroy(Pendapatan $pendapatan)
    {
        $pendapatan->delete();

        return redirect()
            ->route('pendapatan.index')
            ->with('success', 'Pendapatan berhasil dihapus');
    }
}

