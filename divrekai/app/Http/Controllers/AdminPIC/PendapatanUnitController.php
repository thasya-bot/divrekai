<?php

namespace App\Http\Controllers\AdminPIC;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan;
use App\Models\Unit;
use Illuminate\Http\Request;

class PendapatanUnitController extends Controller
{
    public function index(Unit $unit)
    {
        $pendapatan = Pendapatan::where('unit_id', $unit->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $hariIni = Pendapatan::where('unit_id', $unit->id)
            ->whereDate('tanggal', today())
            ->sum('jumlah');

        $bulanIni = Pendapatan::where('unit_id', $unit->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        return view('admin_pic.pendapatan.index', compact(
            'unit',
            'pendapatan',
            'hariIni',
            'bulanIni'
        ));
    }
}
