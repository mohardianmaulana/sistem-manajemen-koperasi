@extends('adminlte::page')

@section('title', 'Tambah SHU Koperasi')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah SHU Koperasi</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-8">

        <div class="mb-3">
            <a href="{{ route('shu-koperasi.index') }}"
               class="btn btn-secondary"
               style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>
        </div>

        <div class="card">

            <div class="card-body">

                <h4>Form SHU Koperasi</h4>

                <form action="{{ route('shu-koperasi.store') }}" method="POST">

                    @csrf

                    {{-- Tahun --}}
                    <div class="form-group">

                        <label>
                            Tahun
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="tahun"
                            class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', $tahun) }}">

                        @error('tahun')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <hr>

                    <h5>Perhitungan Otomatis</h5>

                    <div class="row">

                        {{-- Jasa Simpanan --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Jasa Simpanan</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                    </div>

                                    <input
                                        id="jasa_simpanan"
                                        type="number"
                                        class="form-control"
                                        value="{{ $jasaSimpanan }}"
                                        readonly>

                                </div>

                            </div>

                        </div>

                        {{-- Jasa Pinjaman --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Jasa Pinjaman</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                    </div>

                                    <input
                                        id="jasa_pinjaman"
                                        type="number"
                                        class="form-control"
                                        value="{{ $jasaPinjaman }}"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <h5>Input Pengurus</h5>

                    <div class="row">

                        {{-- Dana Cadangan --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Dana Cadangan
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="dana_cadangan"
                                    name="dana_cadangan"
                                    class="form-control @error('dana_cadangan') is-invalid @enderror"
                                    value="{{ old('dana_cadangan') }}">

                                @error('dana_cadangan')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- Jasa Pengurus --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Jasa Pengurus
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="jasa_pengurus"
                                    name="jasa_pengurus"
                                    class="form-control @error('jasa_pengurus') is-invalid @enderror"
                                    value="{{ old('jasa_pengurus') }}">

                                @error('jasa_pengurus')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- Dana Sosial --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    Dana Sosial
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="dana_sosial"
                                    name="dana_sosial"
                                    class="form-control @error('dana_sosial') is-invalid @enderror"
                                    value="{{ old('dana_sosial') }}">

                                @error('dana_sosial')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- Total SHU --}}
                    <div class="form-group">

                        <label>Total SHU</label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text">

                                    Rp

                                </span>

                            </div>

                            <input
                                id="total_shu"
                                type="number"
                                class="form-control"
                                readonly>

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

@section('js')

<script>

let jasaSimpanan = Number(document.getElementById('jasa_simpanan').value);

let jasaPinjaman = Number(document.getElementById('jasa_pinjaman').value);

function hitungTotalShu(){

    let cadangan = parseInt(document.getElementById('dana_cadangan').value) || 0;

    let pengurus = parseInt(document.getElementById('jasa_pengurus').value) || 0;

    let sosial = parseInt(document.getElementById('dana_sosial').value) || 0;

    let total = jasaSimpanan
              + jasaPinjaman
              + cadangan
              + pengurus
              + sosial;

    document.getElementById('total_shu').value = total;

}

document.getElementById('dana_cadangan').addEventListener('keyup', hitungTotalShu);
document.getElementById('jasa_pengurus').addEventListener('keyup', hitungTotalShu);
document.getElementById('dana_sosial').addEventListener('keyup', hitungTotalShu);

hitungTotalShu();

</script>

@stop