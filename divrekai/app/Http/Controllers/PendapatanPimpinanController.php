<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendapatanPimpinanController extends Controller
{
    public function index(Request $request)
    {
        $unitId = $request->unit_id;

        $tanggalFilter = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)
            : now();

        /*
        |==============================
        | BASE QUERY (SATU SUMBER)
        |==============================
        */
        $baseQuery = Pendapatan::with('unit');

        if ($unitId) {
            $baseQuery->where('unit_id', $unitId);
        }

        /*
        |==============================
        | TABEL HARIAN
        |==============================
        */
        $pendapatan = (clone $baseQuery)
            ->when($request->filled('tanggal'), function ($q) use ($tanggalFilter) {
                $q->whereDate('tanggal', $tanggalFilter);
            })
            ->when($request->filled('search_harian'), function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('keterangan', 'like', "%{$request->search_harian}%")
                       ->orWhereHas('unit', function ($u) use ($request) {
                           $u->where('nama_unit', 'like', "%{$request->search_harian}%");
                       });
                });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate($request->get('limit_harian', 10))
            ->withQueryString();

        /*
        |==============================
        | RINGKASAN
        |==============================
        */
        $hariIni = (clone $baseQuery)
            ->whereDate('tanggal', $tanggalFilter)
            ->sum('jumlah');

        $bulanIni = (clone $baseQuery)
            ->whereMonth('tanggal', $tanggalFilter->month)
            ->whereYear('tanggal', $tanggalFilter->year)
            ->sum('jumlah');

        /*
        |==============================
        | REKAP BULANAN
        |==============================
        */
        $rekapBulanan = (clone $baseQuery)
            ->when($request->filled('tanggal'), function ($q) use ($request) {
                $tgl = Carbon::parse($request->tanggal);
                $q->whereYear('tanggal', $tgl->year)
                  ->whereMonth('tanggal', $tgl->month);
            })
            ->when(
                !$request->filled('tanggal') && $request->filled('bulan'),
                function ($q) use ($request) {
                    [$tahun, $bulan] = explode('-', $request->bulan);
                    $q->whereYear('tanggal', $tahun)
                      ->whereMonth('tanggal', $bulan);
                }
            )
            ->selectRaw('YEAR(tanggal) tahun, MONTH(tanggal) bulan, SUM(jumlah) total')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate($request->get('limit_bulanan', 4), ['*'], 'page_bulanan')
            ->withQueryString();

        /*
        |==============================
        | PIE CHART + PERSENTASE
        |==============================
        */
        if ($request->filled('unit_id')) {

            // 👉 PIE BERDASARKAN SUMBER (DALAM 1 UNIT)
            $pieRaw = (clone $baseQuery)
                ->when($request->filled('tanggal'), function ($q) use ($tanggalFilter) {
                    $q->whereDate('tanggal', $tanggalFilter);
                })
                ->selectRaw('keterangan as label, SUM(jumlah) as total')
                ->groupBy('keterangan')
                ->get();

        } else {

            // 👉 PIE BERDASARKAN UNIT (SEMUA UNIT)
            $pieRaw = (clone $baseQuery)
                ->join('units', 'units.id', '=', 'pendapatan.unit_id')
                ->when($request->filled('tanggal'), function ($q) use ($tanggalFilter) {
                    $q->whereDate('pendapatan.tanggal', $tanggalFilter);
                })
                ->selectRaw('units.nama_unit as label, SUM(pendapatan.jumlah) as total')
                ->groupBy('units.nama_unit')
                ->get();
        }

        // 👉 HITUNG TOTAL KESELURUHAN
        $pieTotal = $pieRaw->sum('total');

        // 👉 TAMBAHKAN PERSENTASE
        $pieData = $pieRaw->map(function ($item) use ($pieTotal) {
            return [
                'label'   => $item->label,
                'total'   => $item->total,
                'percent' => $pieTotal > 0
                    ? round(($item->total / $pieTotal) * 100, 1)
                    : 0
            ];
        });

        /*
        |==============================
        | BAR CHART (HARIAN DALAM BULAN)
        |==============================
        */
        $barData = (clone $baseQuery)
            ->when($request->filled('bulan'), function ($q) use ($request) {
                [$tahun, $bulan] = explode('-', $request->bulan);
                $q->whereYear('tanggal', $tahun)
                  ->whereMonth('tanggal', $bulan);
            }, function ($q) use ($tanggalFilter) {
                $q->whereMonth('tanggal', $tanggalFilter->month)
                  ->whereYear('tanggal', $tanggalFilter->year);
            })
            ->selectRaw('DATE(tanggal) as tanggal, SUM(jumlah) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        /*
        |==============================
        | DROPDOWN UNIT
        |==============================
        */
        $navbarUnits = Unit::orderBy('nama_unit')->get();

        return view('pimpinan.beranda', compact(
            'pendapatan',
            'rekapBulanan',
            'hariIni',
            'bulanIni',
            'navbarUnits',
            'pieData',
            'barData'
        ));
    }
}
