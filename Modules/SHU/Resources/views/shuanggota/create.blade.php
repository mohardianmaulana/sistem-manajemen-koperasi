@extends('adminlte::page')

@section('title', 'Hitung SHU Anggota')

@section('content_header')
<h1 class="m-0 text-dark">Hitung SHU Anggota</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">

        {{-- Tombol Kembali --}}
        <div class="mb-3">

            <a href="{{ route('shu.index') }}"
                class="btn btn-secondary"
                style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

        {{-- Pesan Berhasil --}}
        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        {{-- Pesan Error --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- Informasi --}}
        <div class="alert alert-warning">

            <h5>

                <i class="fas fa-exclamation-triangle"></i>
                Perhatian

            </h5>

            <p class="mb-0">

                Pastikan data SHU Koperasi pada periode yang dipilih
                telah tersedia sebelum melakukan perhitungan SHU anggota.

            </p>

        </div>

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Form Perhitungan SHU Anggota

                </h3>

            </div>

            <div class="card-body">

                <form action="{{ route('shu.store') }}"
                    method="POST">

                    @csrf

                    <div class="row">

                        {{-- Periode Awal --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Periode Awal
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="periode_awal"
                                    class="form-control @error('periode_awal') is-invalid @enderror"
                                    value="{{ old('periode_awal') }}">

                                @error('periode_awal')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                        {{-- Periode Akhir --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Periode Akhir
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="periode_akhir"
                                    class="form-control @error('periode_akhir') is-invalid @enderror"
                                    value="{{ old('periode_akhir') }}">

                                @error('periode_akhir')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                    </div>


                        {{-- Persentase Pajak --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Persentase Pajak (%)
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    name="persen_pajak"
                                    class="form-control @error('persen_pajak') is-invalid @enderror"
                                    value="{{ old('persen_pajak',10) }}">

                                @error('persen_pajak')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                                <small class="text-muted">

                                    Pajak akan dipotong dari total SHU anggota.

                                </small>

                            </div>

                        </div>

                    </div>
                    <div class="card-footer text-right">
        <button
            type="submit"
            id="btnHitung"
            class="btn btn-success"
            style="border-radius:10px">

            <i class="fas fa-calculator"></i>
            Hitung SHU Anggota

        </button>
    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@stop

@section('js')

<script>

document
    .getElementById('btnHitung')
    .addEventListener('click', function(e){

        if(!confirm(
            'Apakah Anda yakin ingin menghitung SHU seluruh anggota pada periode yang dipilih?'
        )){

            e.preventDefault();

        }

    });

</script>

@stop