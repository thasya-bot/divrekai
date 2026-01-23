@extends('layouts.app')

@section('title', 'Pendapatan Unit')

@section('content')
<div class="max-w-8xl mx-auto bg-white p-6 shadow rounded">

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-lg text-[#231f5c] mb-4">
        Kelola Data Unit {{ $unit->nama_unit }}
    </h2>

    {{-- TABEL --}}
    <div class="overflow-x-auto">
        <div class="flex justify-between items-center mb-3 text-sm">

    {{-- FILTER TANGGAL --}}
    <div class="flex gap-2 items-center">
        <label class="font-semibold">Filter:</label>

        <select id="filterBulan" class="border px-2 py-1 rounded">
            <option value="">Bulan</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}">
                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                </option>
            @endfor
        </select>

        <select id="filterTahun" class="border px-2 py-1 rounded">
            <option value="">Tahun</option>
            @for ($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

</div>

        <table id="pendapatanTable"
               class="w-full border border-black border-collapse text-sm">

            <thead class="bg-orange-700 text-white">
                <tr>
                    <th class="border border-black p-2 w-10">No</th>
                    <th class="border border-black p-2">Tanggal</th>
                    <th class="border border-black p-2">Sumber</th>
                    <th class="border border-black p-2">Jumlah</th>
                    <th class="border border-black p-2 w-32">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pendapatan as $row)
                <tr class="hover:bg-gray-100">
                    <td class="border border-black p-2 text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="border border-black p-2 text-center">
                        {{ $row->tanggal }}
                    </td>
                    <td class="border border-black p-2">
                        {{ $row->keterangan }}
                    </td>
                    <td class="border border-black p-2 text-right">
                        Rp {{ number_format($row->jumlah) }}
                    </td>
                    <td class="border border-black p-2 text-center space-x-1">
                        <button
                            class="bg-[#231f5c] text-white px-3 py-1 rounded text-xs">
                            Edit
                        </button>
                        <button
                            class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- TOMBOL TAMBAH DATA --}}
    <div class="flex justify-end mt-4">
        <button
            class="bg-blue-700 text-white px-4 py-2 rounded text-sm">
            + Tambah Data
        </button>
    </div>

</div>

{{-- DATATABLES --}}
<script>
$(document).ready(function () {

    var table = $('#pendapatanTable').DataTable({
        dom: 'lfrtip', // ⬅️ INI KUNCI UTAMA
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ordering: false,
        pagingType: "full_numbers",
        language: {
            lengthMenu: "Show _MENU_ Entries",
            search: "Search:",
            info: "Showing _START_ to _END_ of _TOTAL_ Entries",
            paginate: {
                previous: "Previous",
                next: "Next"
            }
        }
    });

    // 🔎 SEARCH KHUSUS KOLOM TANGGAL
    $('input[type="search"]').on('keyup', function () {
        table
            .columns(1) // kolom Tanggal
            .search(this.value)
            .draw();
    });

});
</script>
@endsection
