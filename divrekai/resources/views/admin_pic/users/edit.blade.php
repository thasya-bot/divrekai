@extends('layouts.app')

@section('title', 'Edit Kelola Pengguna')

@section('content')
<div class="max-w-8xl mx-auto bg-white p-6 shadow rounded">

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-2xl text-[#231f5c] mb-6">
        Edit Kelola Pengguna
    </h2>

    {{-- FORM EDIT --}}
    <form id="formEditUser"
          method="POST"
          action="{{ route('admin.pic.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <table class="w-full border border-black  text-sm">
            <thead class="bg-orange-700 text-white">
                <tr>
                    <th class="border px-3 py-2 w-12">No</th>
                    <th class="border px-3 py-2">Unit</th>
                    <th class="border px-3 py-2">Username</th>
                    <th class="border px-3 py-2">Password</th>
                    <th class="border px-3 py-2 w-32">Action</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="border px-2 py-1 text-center">1</td>

                    {{-- UNIT --}}
                    <td class="border px-2 py-1 text-center">
                        {{ $user->unit->nama_unit ?? '-' }}
                    </td>

                    {{-- USERNAME --}}
                    <td class="border px-2 py-1">
                        <input type="text"
                               name="username"
                               value="{{ $user->username }}"
                               class="w-full text-sm bg-transparent border-0 outline-none focus:ring-0 focus:outline-none"
                               required>
                    </td>

                    {{-- PASSWORD --}}
                    <td class="border px-2 py-1">
                        <input type="password"
                               name="password"
                               placeholder="Kosongkan jika tidak diubah"
                               class="w-full text-sm bg-transparent border-0 outline-none focus:ring-0 focus:outline-none">
                    </td>

                    {{-- ACTION --}}
                    <td class="border px-2 py-1 text-center space-x-1">
                        <button type="button"
                                onclick="confirmEditUser()"
                                class="bg-[#231f5c] text-white px-3 py-1 rounded text-xs">
                            Simpan
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//KONFIRMASI SIMPAN
function confirmEditUser() {
    Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: 'Apakah Anda yakin ingin menyimpan perubahan data pengguna ini?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl px-6 py-6',
            title: 'text-[#231f5c] font-bold text-lg',
            actions: 'flex gap-4 justify-center mt-6',
            confirmButton: 'w-40 py-2 rounded-lg text-white font-semibold bg-orange-600 hover:bg-orange-700',
            cancelButton: 'w-40 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditUser').submit();
        }
    });
}

</script>
@endsection
