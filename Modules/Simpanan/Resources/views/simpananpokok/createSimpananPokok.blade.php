@extends('adminlte::page')

@section('title', 'Tambah Simpanan Pokok')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Simpanan Pokok</h1>
@stop

@section('content')

<div class="row">
    <div class="col-12">

        <div class="mb-3">
            <a href="{{ route('simpanan-pokok.index') }}"
               class="btn btn-secondary"
               style="border-radius:10px">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <h4>Form Tabungan</h4>

                <form action="{{ route('simpanan-pokok.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        {{-- USER --}}
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>
                                    User
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="id_anggota"
                                    class="form-control @error('id_anggota') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih User --
                                    </option>

                                    @foreach($users as $user)

                                        <option value="{{ $user->id }}"
                                            {{ old('id_anggota') == $user->id ? 'selected' : '' }}>

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
                                    Nominal Tabungan
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
                                        placeholder="Masukkan nominal tabungan">

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
                                    Tanggal Menabung
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
                            class="btn btn-primary"
                            style="border-radius:10px">

                            <i class="fas fa-save"></i>
                            Simpan

                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@stop