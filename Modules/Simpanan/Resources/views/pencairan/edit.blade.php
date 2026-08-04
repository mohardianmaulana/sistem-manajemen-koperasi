@extends('adminlte::page')

@section('title', 'Edit Pengajuan Pencairan')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>Edit Pengajuan Pencairan</h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">

                        <a href="{{ route('pencairan-simpanan.index') }}">

                            Pencairan Simpanan

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Edit

                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

<div class="container-fluid">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card">

<div class="card-header">

<h3 class="card-title">

Form Edit Pengajuan Pencairan

</h3>

</div>

<form
    action="{{ route('pencairan-simpanan.update', $data->id) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card-body">

        <div class="form-group">

            <label>

                Nominal Pencairan

            </label>

            <input
                type="number"
                name="nominal_pencairan"
                class="form-control @error('nominal_pencairan') is-invalid @enderror"
                value="{{ old('nominal_pencairan', $data->nominal_pencairan) }}"
                min="1000">

            @error('nominal_pencairan')

                <span class="invalid-feedback d-block">

                    {{ $message }}

                </span>

            @enderror

        </div>

        <div class="form-group">

            <label>

                Alasan Pencairan

            </label>

            <textarea
                name="alasan"
                rows="4"
                class="form-control @error('alasan') is-invalid @enderror">{{ old('alasan', $data->alasan) }}</textarea>

            @error('alasan')

                <span class="invalid-feedback d-block">

                    {{ $message }}

                </span>

            @enderror

        </div>

    </div>

    <div class="card-footer text-right">

        <a
            href="{{ route('pencairan-simpanan.index') }}"
            class="btn btn-secondary">

            Batal

        </a>

        <button
            type="submit"
            class="btn btn-primary">

            <i class="fas fa-save mr-1"></i>

            Simpan Perubahan

        </button>

    </div>

</form>

</div>

</div>

</div>

</div>

</section>

@endsection