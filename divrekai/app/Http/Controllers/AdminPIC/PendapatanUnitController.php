<?php

namespace App\Http\Controllers\AdminPIC;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan;
use App\Models\Unit;
use Illuminate\Http\Request;

class PendapatanUnitController extends Controller
{
    // =====================
    // LIST + FILTER
    // =====================
    public function index(Request $request, Unit $unit)
    {
        $limit  = $request->input('limit', 4);
        $search = $request->input('search');

        $query = Pendapatan::where('unit_id', $unit->id);

        // 🔍 SEARCH KHUSUS TANGGAL & SUMBER
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tanggal', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $pendapatan = $query
            ->orderBy('tanggal', 'desc')
            ->paginate($limit)
            ->withQueryString();

        // RINGKASAN
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

    // =====================
    // EDIT
    // =====================
    public function edit(Pendapatan $pendapatan)
    {
        $unit = Unit::findOrFail($pendapatan->unit_id);

        return view('admin_pic.pendapatan.edit', compact(
            'pendapatan',
            'unit'
        ));
    }

    // =====================
    // UPDATE
    // =====================
    public function update(Request $request, Pendapatan $pendapatan)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jumlah'     => 'required|numeric',
        ]);

        $pendapatan->update([
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah'     => $request->jumlah,
        ]);

        return redirect()
            ->route('admin.pic.unit.pendapatan', $pendapatan->unit_id)
            ->with('success', 'Data pendapatan berhasil diperbarui');

    }

    // =====================
    // HAPUS
    // =====================
    public function destroy(Pendapatan $pendapatan)
    {
        $pendapatan->delete();

        return back()->with('success', 'Data pendapatan berhasil dihapus');
    }
}
