<?php

namespace App\Http\Controllers;

use App\Models\TargetPendapatan;
use Illuminate\Http\Request;

class TargetPendapatanController extends Controller
{
    // SET TARGET
    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|in:harian,bulanan,tahunan',
            'target_jumlah' => 'required|numeric',
            'tanggal' => 'nullable|date',
            'bulan' => 'nullable|integer',
            'tahun' => 'required|integer',
        ]);

        TargetPendapatan::updateOrCreate(
            [
                'unit_id' => auth()->user()->unit_id,
                'periode' => $request->periode,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'tanggal' => $request->tanggal,
            ],
            [
                'target_jumlah' => $request->target_jumlah,
            ]
        );

        return back()->with('success', 'Target berhasil disimpan');
    }
}