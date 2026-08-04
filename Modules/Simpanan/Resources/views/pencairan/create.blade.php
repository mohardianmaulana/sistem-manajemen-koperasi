@extends('adminlte::page')

@section('title', 'Pengajuan Pencairan Simpanan')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>Pengajuan Pencairan Simpanan</h1>
            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">
                        <a href="{{ route('pencairan-simpanan.index') }}">
                            Pencairan Simpanan
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Pengajuan
                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-4">

                <div class="small-box bg-success">

                    <div class="inner">

                        <h3>

                            Rp {{ number_format($saldo,0,',','.') }}

                        </h3>

                        <p>

                            Saldo Simpanan

                        </p>

                        <small>

                            Saldo maksimal yang dapat diajukan.

                        </small>

                    </div>

                    <div class="icon">

                        <i class="fas fa-wallet"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Form Pengajuan

                </h3>

            </div>

            <form
                action="{{ route('pencairan-simpanan.store') }}"
                method="POST">

                @csrf

                <div class="card-body">

                    <div class="form-group">

                        <label>

                            Nominal Pencairan

                        </label>

                        <input
                            type="number"
                            name="nominal_pencairan"
                            value="{{ old('nominal_pencairan') }}"
                            class="form-control @error('nominal_pencairan') is-invalid @enderror"
                            placeholder="Masukkan nominal">

                        @error('nominal_pencairan')

                        <span class="invalid-feedback">

                            {{ $message }}

                        </span>

                        @enderror

                    </div>

                    <div class="form-group">

                        <label>

                            Alasan

                        </label>

                        <textarea
                            name="alasan"
                            rows="4"
                            class="form-control @error('alasan') is-invalid @enderror"
                            placeholder="Masukkan alasan pencairan">{{ old('alasan') }}</textarea>

                        @error('alasan')

                        <span class="invalid-feedback">

                            {{ $message }}

                        </span>

                        @enderror

                    </div>

                </div>

                <div class="card-footer text-right">

                    <a
                        href="{{ route('pencairan-simpanan.index') }}"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-paper-plane mr-1"></i>

                        Ajukan Pencairan

                    </button>

                </div>

            </form>

        </div>

    </div>

</section>

@endsection