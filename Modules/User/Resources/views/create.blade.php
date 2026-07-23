@extends('adminlte::page')

@section('title', 'Pendaftaran Anggota')

@section('content_header')
<h1 class="m-0 text-dark">Pendaftaran Anggota</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">

        <div class="mb-3">
            <a href="{{ route('login.show') }}"
                class="btn btn-secondary"
                style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>
        </div>

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">Formulir Pendaftaran Anggota</h5>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>
                @endif

                <form
                    action="{{ route('user.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="form-group">

                        <label>
                            Nama Lengkap
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label>
                            NIP / NIK / NIPPPK
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nip"
                            value="{{ old('nip') }}"
                            class="form-control @error('nip') is-invalid @enderror">

                        @error('nip')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

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
                                    value="{{ old('tempat_lahir') }}"
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
                                    value="{{ old('tanggal_lahir') }}"
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
                            class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>

                        @error('alamat')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label>
                            Nomor Telepon / HP
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            class="form-control @error('no_hp') is-invalid @enderror">

                        @error('no_hp')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

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
                                    {{ old('unit') == $unit->id ? 'selected' : '' }}>

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

                    <div class="form-group">

                        <label>
                            Upload SK Perjanjian Kerja
                        </label>

                        <input
                            type="file"
                            name="file_sk"
                            class="form-control @error('file_sk') is-invalid @enderror">

                        <small class="text-muted">
                            Format PDF, JPG, JPEG atau PNG (maksimal 2 MB)
                        </small>

                        @error('file_sk')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">

                        <button
                            type="submit"
                            class="btn btn-primary"
                            style="border-radius:10px">

                            <i class="fas fa-save"></i>
                            Daftar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@stop