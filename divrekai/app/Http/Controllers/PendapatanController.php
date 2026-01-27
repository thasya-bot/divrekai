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
    $targetHarian = 3200000;

    $pieData = Pendapatan::selectRaw('
    keterangan,
    SUM(jumlah) as total
    ')
    ->where('unit_id', $unitId)
    ->whereMonth('tanggal', now()->month)
    ->whereYear('tanggal', now()->year)
    ->groupBy('keterangan')
    ->get();

    $barData = Pendapatan::selectRaw('
        DATE(tanggal) as tanggal,
        SUM(jumlah) as total
    ')
    ->where('unit_id', $unitId)
    ->whereMonth('tanggal', now()->month)
    ->whereYear('tanggal', now()->year)
    ->groupBy('tanggal')
    ->orderBy('tanggal')
    ->get();


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
// =====================
// TARGET & STATUS HARIAN
// =====================
$targetHarian = 3200000;
$statusTargetHarian = null;

$now = now();

// Senin–Jumat
$isHariKerja = $now->isWeekday();

// Jam 16:30 – 23:59
$isJamWarning = $now->between(
    $now->copy()->setTime(16, 30),
    $now->copy()->setTime(23, 59)
);

if ($isHariKerja && $isJamWarning) {
    if ($hariIni < $targetHarian) {
        $statusTargetHarian = 'kurang';
    } elseif ($hariIni == $targetHarian) {
        $statusTargetHarian = 'pas';
    } else {
        $statusTargetHarian = 'lebih';
    }
}

    // PIE CHART (contoh: per keterangan)
    $pieData = Pendapatan::where('unit_id', $unitId)
        ->selectRaw('keterangan, SUM(jumlah) as total')
        ->groupBy('keterangan')
        ->get();

    // BAR CHART (contoh: per tanggal)
    $barData = Pendapatan::where('unit_id', $unitId)
        ->selectRaw('tanggal, SUM(jumlah) as total')
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->limit(5)
        ->get();
        return view('admin_unit.pendapatan.index', compact(
            'pendapatan',
            'hariIni',
            'bulanIni',
            'rekapBulanan',
            'pieData',
            'barData',
            'targetHarian',
            'statusTargetHarian'
        ));

}

public function create()
{
    $unitId = auth()->user()->unit_id;
    $targetHarian = 3200000;

    $totalHariIni = Pendapatan::where('unit_id', $unitId)
        ->whereDate('tanggal', today())
        ->sum('jumlah');

    $now = now();

    $isHariKerja = $now->isWeekday(); // Senin–Jumat
    $isJamWarning = $now->between(
        $now->copy()->setTime(16, 30),
        $now->copy()->setTime(23, 59)
    );

    $statusTargetHarian = null;

    if ($isHariKerja && $isJamWarning && $totalHariIni < $targetHarian) {
        $statusTargetHarian = 'kurang';
    }

    return view('admin_unit.pendapatan.input', compact(
        'totalHariIni',
        'targetHarian',
        'statusTargetHarian'
    ));
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


