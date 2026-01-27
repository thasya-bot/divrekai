@extends('layouts.app')

@section('title', 'Pendapatan Unit')

@section('content')
<div class="max-w-8xl mx-auto bg-white p-6 shadow rounded">

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-2xl mb-4 text-[#231f5c]">
        Kelola Data Unit {{ $unit->nama_unit }}
    </h2>

    {{-- FORM FILTER (SHOW & SEARCH) --}}
    <form id="filterForm" method="GET"
      class="flex justify-between items-center mb-4 text-sm">

    {{-- KIRI : SHOW ENTRIES --}}
    <div class="flex items-center gap-2">
        <span>Show</span>
        <select name="limit"
                onchange="this.form.submit()"
                class="border rounded px-2 py-1">
            @foreach([4,10,25,50] as $l)
                <option value="{{ $l }}"
                    {{ request('limit',4)==$l ? 'selected' : '' }}>
                    {{ $l }}
                </option>
            @endforeach
        </select>
        <span>Entries</span>
    </div>

    {{-- KANAN : SEARCH --}}
    <div class="flex items-center gap-2">
        <span>Search:</span>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari tanggal / sumber..."
               class="border rounded px-2 py-1 text-sm"
               onkeydown="if(event.key==='Enter') this.form.submit()">
    </div>
    </form>


    {{-- TABEL --}}
    <table class="w-full border border-black border-collapse text-sm">

        <thead class="bg-orange-700 text-white">
            <tr>
                <th class="border px-4 py-2 w-12">No</th>
                <th class="border px-4 py-2 w-36">Tanggal</th>
                <th class="border px-4 py-2">Sumber</th>
                <th class="border px-4 py-2 w-80">Jumlah</th>
                <th class="border px-4 py-2 w-40">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pendapatan as $row)
            <tr>
                <td class="border px-2 py-1 text-center">
                    {{ $loop->iteration + ($pendapatan->firstItem() - 1) }}
                </td>
                <td class="border px-2 py-1 text-center">
                    {{ $row->tanggal }}
                </td>
                <td class="border px-2 py-1">
                    {{ $row->keterangan }}
                </td>
                <td class="border px-2 py-1 text-right">
                    Rp {{ number_format($row->jumlah) }}
                </td>
            <td class="border px-2 py-2 text-center space-x-2">

    {{-- EDIT --}}
    <a href="{{ route('admin.pic.pendapatan.edit', $row->id) }}"
       class="bg-[#231f5c] text-white px-2 py-1 rounded text-xs inline-block">
        Edit
    </a>

    {{-- HAPUS --}}
    <form id="deleteForm{{ $row->id }}"
        action="{{ route('admin.pic.pendapatan.destroy', $row->id) }}"
        method="POST"
        class="inline">
        @csrf
        @method('DELETE')

        <button type="button"
                onclick="confirmDelete({{ $row->id }})"
                class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
            Hapus
        </button>
    </form>
</td>
</tr>
        @empty
            <tr>
                <td colspan="5" class="border px-2 py-4 text-center text-gray-500">
                    Data tidak ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- INFO & PAGINATION --}}
    <div class="flex justify-between items-center mt-10 rounded-lg text-sm">
        <div>
            Showing {{ $pendapatan->firstItem() }}
            to {{ $pendapatan->lastItem() }}
            of {{ $pendapatan->total() }} Entries
        </div>

        <div>
            {{ $pendapatan->withQueryString()->links() }}
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `
            <p class="text-gray-700 text-sm leading-relaxed">
                Apakah Anda yakin ingin menghapus data pendapatan ini?
            </p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        icon: undefined,

        customClass: {
            popup: 'rounded-2xl px-6 py-6',
            title: 'text-[#231f5c] font-bold text-lg',
            actions: 'flex gap-4 justify-center mt-6',
            confirmButton: 'w-40 py-2 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700',
            cancelButton: 'w-40 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100'
        },

        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endsection
