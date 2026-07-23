@extends('adminlte::page')

@section('title', 'Verifikasi Anggota')

@section('content_header')
    <h1 class="m-0 text-dark">Verifikasi & Aktivasi Anggota</h1>
@stop

@section('content')

<div class="row">

    <div class="col-md-10">

        <div class="mb-3">

            <a href="{{ route('user.index') }}"
               class="btn btn-secondary"
               style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('user.update', $user->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="card">

                <div class="card-header bg-primary">

                    <h5 class="mb-0">

                        <i class="fas fa-user"></i>
                        Data Pendaftaran Anggota

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Nama Lengkap
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror">

                                @error('name')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    NIP / NIK / NIPPPK
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="nip"
                                    value="{{ old('nip', $user->nip) }}"
                                    class="form-control @error('nip') is-invalid @enderror">

                                @error('nip')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Tempat Lahir
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                    class="form-control @error('tempat_lahir') is-invalid @enderror">

                                @error('tempat_lahir')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Tanggal Lahir
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror">

                                @error('tanggal_lahir')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label>
                            Alamat Rumah
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>

                        @error('alamat')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Nomor Telepon / HP
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    value="{{ old('no_hp', $user->no_hp) }}"
                                    class="form-control @error('no_hp') is-invalid @enderror">

                                @error('no_hp')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Unit Kerja
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="unit"
                                    class="form-control @error('unit') is-invalid @enderror">

                                    <option value="">-- Pilih Unit Kerja --</option>

                                    @foreach($units as $unit)

                                        <option
                                            value="{{ $unit->id }}"
                                            {{ old('unit', $user->unit) == $unit->id ? 'selected' : '' }}>

                                            {{ $unit->nama }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('unit')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label>
                            Upload SK Perjanjian Kerja
                        </label>

                        <input
                            type="file"
                            name="file_sk"
                            class="form-control @error('file_sk') is-invalid @enderror">

                        @error('file_sk')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if($user->file_sk)

                            <div class="mt-3">

                                <a href="{{ asset('storage/'.$user->file_sk) }}"
                                   target="_blank"
                                   class="btn btn-info">

                                    <i class="fas fa-file"></i>
                                    Lihat File SK

                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

            <div class="card mt-4">

    <div class="card-header bg-success">

        <h5 class="mb-0">

            <i class="fas fa-user-check"></i>
            Aktivasi Akun

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Username
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        class="form-control @error('username') is-invalid @enderror">

                    @error('username')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Email
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

        </div>

        <div class="row">

            {{-- Password --}}
            <div class="col-md-6">

                <div class="form-group">

                    <label>Password</label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Masukkan password baru"
                            class="form-control @error('password') is-invalid @enderror">

                        <div class="input-group-append">

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="togglePassword"
                                title="Tampilkan Password">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                    </div>

                    @error('password')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            {{-- Konfirmasi Password --}}
            <div class="col-md-6">

                <div class="form-group">

                    <label>Konfirmasi Password</label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Konfirmasi password"
                            class="form-control">

                        <div class="input-group-append">

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="togglePasswordConfirmation"
                                title="Tampilkan Password">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Role
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="role"
                        class="form-control @error('role') is-invalid @enderror">

                        <option value="">-- Pilih Role --</option>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->name }}"
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>

                                {{ ucfirst($role->name) }}

                            </option>

                        @endforeach

                    </select>

                    @error('role')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label>Staff</label>

                    <select
                        name="staff"
                        class="form-control @error('staff') is-invalid @enderror">

                        <option value="">-- Pilih Staff --</option>

                        @foreach($staffs as $staff)

                            <option
                                value="{{ $staff->id }}"
                                {{ old('staff', $user->staff) == $staff->id ? 'selected' : '' }}>

                                {{ $staff->nama }}

                            </option>

                        @endforeach

                    </select>

                    @error('staff')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Nomor Rekening
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="no_rek"
                        value="{{ old('no_rek', $user->no_rek) }}"
                        class="form-control @error('no_rek') is-invalid @enderror">

                    @error('no_rek')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-control @error('status') is-invalid @enderror">

                        <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>

                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                            Internal
                        </option>

                        <option value="2" {{ old('status', $user->status) == 2 ? 'selected' : '' }}>
                            Eksternal
                        </option>

                    </select>

                    @error('status')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-body">

        <div class="d-flex justify-content-between">

            <a
                href="{{ route('user.index') }}"
                class="btn btn-secondary"
                style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

            <button
                type="submit"
                class="btn btn-primary"
                style="border-radius:10px">

                <i class="fas fa-save"></i>
                Simpan Perubahan

            </button>

        </div>

    </div>

</div>

</form>

</div>

</div>

@stop

@section('js')
<script>

document.addEventListener('DOMContentLoaded', function () {

    function togglePassword(buttonId, inputId) {

        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (!button || !input) return;

        button.addEventListener('click', function () {

            const icon = this.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

                this.setAttribute('title', 'Sembunyikan Password');

            } else {

                input.type = 'password';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                this.setAttribute('title', 'Tampilkan Password');

            }

        });

    }

    togglePassword('togglePassword', 'password');
    togglePassword('togglePasswordConfirmation', 'password_confirmation');

});

</script>
@stop