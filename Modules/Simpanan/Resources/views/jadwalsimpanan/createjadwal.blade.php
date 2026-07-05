@extends('adminlte::page')

@section('title', 'Tambah Jenis Simpanan')

@section('content_header')
<h1 class="m-0 text-dark">Tambah Jenis Simpanan</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">

        <div class="mb-3">
            <a href="{{ route('master-jenis-simpanan.index') }}"
                class="btn btn-secondary"
                style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>
        </div>

        <div class="card">

            <div class="card-body">

                <form action="{{ route('master-jenis-simpanan.store') }}" method="POST">
                    @csrf

                    <div class="form-group">

                        <label>
                            Jenis Simpanan
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="nama_jenis_simpanan"
                            class="form-control @error('nama_jenis_simpanan') is-invalid @enderror">

                            <option value="">-- Pilih Jenis Simpanan --</option>

                            <option value="Simpanan Pokok"
                                {{ old('nama_jenis_simpanan') == 'Simpanan Pokok' ? 'selected' : '' }}>
                                Simpanan Pokok
                            </option>

                            <option value="Simpanan Wajib"
                                {{ old('nama_jenis_simpanan') == 'Simpanan Wajib' ? 'selected' : '' }}>
                                Simpanan Wajib
                            </option>

                            <option value="Simpanan Sukarela"
                                {{ old('nama_jenis_simpanan') == 'Simpanan Sukarela' ? 'selected' : '' }}>
                                Simpanan Sukarela
                            </option>

                        </select>

                        @error('nama_jenis_simpanan')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Tanggal Mulai
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="datetime-local"
                                    name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai') }}"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror">

                                @error('tanggal_mulai')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Tanggal Berakhir
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="datetime-local"
                                    name="tanggal_berakhir"
                                    value="{{ old('tanggal_berakhir') }}"
                                    class="form-control @error('tanggal_berakhir') is-invalid @enderror">

                                @error('tanggal_berakhir')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

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