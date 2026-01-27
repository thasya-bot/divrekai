@extends('layouts.app')

@section('title', 'Data Unit')

@section('content')
<div class="max-w-8xl mx-auto p-8 bg-white shadow rounded">

    <h2 class="text-center font-bold text-3xl mb-4 text-[#231f5c]">
        Daftar Unit
    </h2>

    <table class="w-full border border-gray-300 border-collapse">
        <thead class="bg-orange-600 text-white">
            <tr>
                <th class="p-2 border border-gray-300">No</th>
                <th class="p-2 border border-gray-300">ID Unit</th>
                <th class="p-2 border border-gray-300">Nama Unit</th>
                <th class="p-2 border border-gray-300">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($units as $unit)
        <tr class="border-t hover:bg-gray-200">
            <td class="p-2 border border-gray-300 text-center">
                {{ $loop->iteration }}
            </td>

            <td class="p-2 border border-gray-300 text-center">
                {{ $unit->id }}
            </td>

            <td class="p-2 border border-gray-300">
                {{ $unit->nama_unit }}
            </td>

            <td class="p-2 border border-gray-300 text-center">
                <a href="{{ route('admin.pic.unit.pendapatan', $unit->id) }}"
                class="bg-[#231f5c] text-white px-2 py-1 rounded">
                    Lihat Pendapatan
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>

    </table>

</div>
@endsection
