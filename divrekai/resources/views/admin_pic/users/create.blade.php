@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-xl mx-auto mt-10">

    {{-- JUDUL --}}
    <h2 class="text-center font-bold text-3xl text-[#231f5c] mb-6">
        Tambah Pengguna
    </h2>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#231f5c] px-6 py-3">
            <h3 class="text-white font-semibold text-sm">
                Form Tambah Pengguna
            </h3>
        </div>

        {{-- FORM --}}
        <form id="formTambahUser"
              method="POST"
              action="{{ route('admin.pic.users.store') }}"
              class="p-6 space-y-4">
            @csrf

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Username
                </label>
                <input type="text"
                       name="username"
                       value="{{ old('username') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                       required>
                @error('username')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Password
                </label>
                <input type="password"
                       name="password"
                       class="w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                       required>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Role
                </label>
                <select name="role_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#231f5c]"
                        required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->username }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="pt-2">
                <button type="button"
                        onclick="confirmTambahUser()"
                        class="w-full bg-orange-600 hover:bg-orange-700
                               text-white font-semibold py-2 rounded-lg transition">
                    Simpan Pengguna
                </button>
            </div>

        </form>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmTambahUser() {
    Swal.fire({
        title: 'Konfirmasi Simpan',
        text: 'Apakah Anda yakin ingin menambahkan pengguna ini?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',

        buttonsStyling: false,

        customClass: {
            popup: 'rounded-xl px-6 py-6',
            title: 'text-[#231f5c] font-bold text-lg',
            actions: 'flex gap-4 justify-center mt-6',
            confirmButton:
                'w-40 py-2 rounded-lg text-white font-semibold bg-orange-600 hover:bg-orange-700',
            cancelButton:
                'w-40 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formTambahUser').submit();
        }
    });
}
</script>
@endsection
