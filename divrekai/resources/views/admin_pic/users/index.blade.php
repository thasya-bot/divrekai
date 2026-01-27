@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="max-w-8xl mx-auto bg-white p-6 shadow rounded">

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-3xl text-[#231f5c] mb-4">
        Kelola Pengguna
    </h2>

    {{-- FILTER --}}
    <form method="GET" class="flex justify-between items-center mb-4 text-sm">
        <div class="flex gap-2 items-center">
            <span>Show</span>
            <select name="limit"
                    onchange="this.form.submit()"
                    class="border rounded px-2 py-1">
                @foreach([5,10,25,50] as $l)
                    <option value="{{ $l }}"
                        {{ request('limit',10)==$l ? 'selected' : '' }}>
                        {{ $l }}
                    </option>
                @endforeach
            </select>
            <span>Entries</span>
        </div>

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari username"
               class="border rounded-lg px-2 py-1 text-sm">
    </form>

    {{-- TABLE --}}
    <table class="w-full border border-black border-collapse text-sm">
        <thead class="bg-orange-700 text-white">
            <tr>
                <th class="border px-3 py-2 w-12">No</th>
                <th class="border px-3 py-2">Username</th>
                <th class="border px-3 py-2">Password</th>
                <th class="border px-3 py-2">Role</th>
                <th class="border px-3 py-2 w-40">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($users as $u)
            <tr>
                <td class="border px-2 py-2 text-center">
                    {{ $loop->iteration + ($users->firstItem() - 1) }}
                </td>

                <td class="border px-2 py-2">
                    {{ $u->username }}
                </td>

                {{-- PASSWORD --}}
                <td class="border px-2 py-2 text-center italic text-gray-500">
                    ********
                </td>

                <td class="border px-2 py-2 text-center">
                    {{ $u->role->username }}
                </td>

                <td class="border px-2 py-2 text-center space-x-2">
                    {{-- EDIT --}}
                    <a href="{{ route('admin.pic.users.edit', $u->id) }}"
                       class="bg-[#231f5c] text-white px-3 py-1 rounded text-xs">
                        Edit
                    </a>

                    {{-- HAPUS --}}
                    <form id="deleteUser{{ $u->id }}"
                          action="{{ route('admin.pic.users.destroy', $u->id) }}"
                          method="POST"
                          class="inline">
                        @csrf
                        @method('DELETE')

                        <button type="button"
                                onclick="confirmDeleteUser({{ $u->id }}, '{{ $u->username }}')"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5"
                    class="border px-4 py-6 text-center text-gray-500">
                    Data pengguna tidak ditemukan
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- TAMBAH USER --}}
    <div class="mt-6 text-right">
        <a href="{{ route('admin.pic.users.create') }}"
           class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded text-sm">
            + Tambah Pengguna
        </a>
    </div> 

    {{-- PAGINATION --}}
    <div class="flex justify-between items-center mt-8 rounded-lg text-sm">
        <div>
            Showing {{ $users->firstItem() }}
            to {{ $users->lastItem() }}
            of {{ $users->total() }} entries
        </div>

        <div>
            {{ $users->withQueryString()->links() }}
        </div>
    </div>

</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDeleteUser(id, username) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `
            <p class="text-gray-700 text-sm">
                Apakah Anda yakin ingin menghapus pengguna
                <b>${username}</b>?
            </p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',

        buttonsStyling: false,
        reverseButtons: true,

        customClass: {
            popup: 'rounded-2xl px-6 py-6',
            title: 'text-[#231f5c] font-bold text-lg',
            actions: 'flex gap-4 justify-center mt-6',
            confirmButton: 'w-40 py-2 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700',
            cancelButton: 'w-40 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteUser' + id).submit();
        }
    });
}
</script>
@endsection
