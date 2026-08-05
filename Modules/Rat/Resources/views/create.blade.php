@extends('adminlte::page')

@section('title', 'Tambah RAT')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>Tambah Rapat Anggota Tahunan</h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home.index') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="{{ route('rat.index') }}">

                            RAT

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Tambah RAT

                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Form Tambah RAT
                </h3>

            </div>

            <form
                action="{{ route('rat.store') }}"
                method="POST">

                @csrf

                <div class="card-body">

                    <div class="form-group">

                        <label>

                            Tahun

                        </label>

                        <input
                            type="number"
                            name="tahun"
                            class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', now()->year) }}"
                            min="2000"
                            max="2100"
                            required>

                        @error('tahun')

                            <span class="invalid-feedback">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>

                    <div class="form-group">

                        <label>

                            Tanggal RAT

                        </label>

                        <input
                            type="date"
                            name="tanggal_rat"
                            class="form-control @error('tanggal_rat') is-invalid @enderror"
                            value="{{ old('tanggal_rat') }}"
                            required>

                        @error('tanggal_rat')

                            <span class="invalid-feedback">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>

                    <div class="form-group">

                        <label>

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            required>

                            <option value="">

                                -- Pilih Status --

                            </option>

                            <option
                                value="belum"
                                {{ old('status') == 'belum' ? 'selected' : '' }}>

                                Belum

                            </option>

                            <option
                                value="selesai"
                                {{ old('status') == 'selesai' ? 'selected' : '' }}>

                                Selesai

                            </option>

                        </select>

                        @error('status')

                            <span class="invalid-feedback">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>

                </div>

                <div class="card-footer">

                    <a
                        href="{{ route('rat.index') }}"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary float-right">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</section>

@endsection