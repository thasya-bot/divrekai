<?php

namespace App\Http\Controllers\AdminPIC;

use App\Http\Controllers\Controller;
use App\Models\Unit;

class UnitController extends Controller
{
    // tampilkan semua unit
    public function index()
    {
        $units = Unit::orderBy('nama_unit')->get();

        return view('admin_pic.unit.index', compact('units'));
    }
}
