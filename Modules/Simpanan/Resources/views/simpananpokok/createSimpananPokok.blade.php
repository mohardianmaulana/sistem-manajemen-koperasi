@extends('adminlte::page')

@section('title', 'Tambah Simpanan Pokok')

@section('plugins.Select2', true)

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Simpanan Pokok</h1>
@stop

@section('content')

<div class="row">

    <div class="col-12">

        <div class="mb-3">

            <a href="{{ route('simpanan-pokok.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    Form Tambah Simpanan Pokok
                </h5>

            </div>

            <div class="card-body">

                <form
                    action="{{ route('simpanan-pokok.store') }}"
                    method="POST">

                    @csrf

                    <div class="row">

                        {{-- ANGGOTA --}}
                        <div class="col-md-12">

                            <div class="form-group">

                                <label>
                                    Anggota
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="id_anggota"
                                    id="id_anggota"
                                    class="form-control select2 @error('id_anggota') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Anggota --
                                    </option>

                                    @foreach($users as $user)

                                        <option
                                            value="{{ $user->id }}"
                                            {{ old('id_anggota') == $user->id ? 'selected' : '' }}>

                                            {{ $user->nip }}
                                            -
                                            {{ $user->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('id_anggota')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                        {{-- NOMINAL --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Nominal Simpanan Pokok
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">

                                        <span class="input-group-text">

                                            Rp

                                        </span>

                                    </div>

                                    <input
                                        type="number"
                                        name="nilai"
                                        class="form-control @error('nilai') is-invalid @enderror"
                                        value="{{ old('nilai') }}"
                                        placeholder="Masukkan nominal simpanan">

                                </div>

                                @error('nilai')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Tanggal Simpanan
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="datetime-local"
                                    name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}">

                                @error('tanggal')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="mt-3">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fas fa-save"></i>
                            Simpan

                        </button>

                        <a
                            href="{{ route('simpanan-pokok.index') }}"
                            class="btn btn-secondary">

                            Batal

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@stop
@section('css')
<style>

.select2-container{
    width:100%!important;
}

.select2-container--default .select2-selection--single{
    height: calc(2.25rem + 2px) !important;
    border:1px solid #ced4da !important;
    border-radius:.25rem !important;
    padding:.375rem .75rem !important;
    display:flex;
    align-items:center;
}

/* Warna teks anggota */
.select2-container--default .select2-selection--single .select2-selection__rendered{
    color:#007bff !important; /* Biru Bootstrap */
    font-weight:600;
    line-height:1.5 !important;
    padding-left:0 !important;
    padding-right:35px !important;
}

/* Tombol silang */
.select2-container--default .select2-selection--clear{
    color:#007bff !important;
    font-size:18px;
    font-weight:bold;
    position:absolute;
    right:28px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

/* Efek ketika diarahkan mouse */
.select2-container--default .select2-selection--clear:hover{
    color:#dc3545 !important;
}

/* Icon panah */
.select2-container--default .select2-selection--single .select2-selection__arrow{
    height:100% !important;
    right:8px !important;
}

/* Border ketika dipilih */
.select2-container--default.select2-container--focus .select2-selection--single{
    border-color:#80bdff !important;
    box-shadow:0 0 0 .2rem rgba(0,123,255,.25);
}

</style>
@stop

@section('js')

<script>

$(document).ready(function () {

   $('#id_anggota').select2({
    placeholder: 'Cari anggota...',
    allowClear: true,
    width: '100%'
});

});

</script>

@stop