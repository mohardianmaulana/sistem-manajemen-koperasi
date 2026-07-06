@extends('adminlte::page')

@section('title', 'Tambah Simpanan Sukarela')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Simpanan Sukarela</h1>
@stop

@section('content')

<div class="row">
<div class="col-12">

    <div class="mb-3">
        <a href="{{ route('simpanan-sukarela.index') }}"
           class="btn btn-secondary"
           style="border-radius:10px">

            <i class="fas fa-arrow-left"></i>
            Kembali

        </a>
    </div>

    <div class="card">
    <div class="card-body">

        <h4>Form Simpanan Wajib</h4>

        <form action="{{ route('simpanan-wajib.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="row">

                {{-- NILAI --}}
                <div class="col-md-6">
                    <div class="form-group">

                        <label>Nominal Simpanan *</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>

                           <input type="number"
                                    name="nilai"
                                    class="form-control"
                                    value="{{ old('nilai') }}"
                                    placeholder="Masukkan nominal">

                        </div>

                        @error('nilai')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

                {{-- PERIODE --}}
                <div class="col-md-6">
                    <div class="form-group">

                        <label>Periode *</label>

                        <input type="date"
                               name="periode"
                               class="form-control @error('periode') is-invalid @enderror"
                               value="{{ old('periode') }}">

                        @error('periode')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

            </div>

            {{-- TAHUN (opsional, bisa nanti dihapus juga) --}}
            <div class="form-group">

                <label>Tahun</label>

                <input type="number"
                       name="tahun"
                       class="form-control @error('tahun') is-invalid @enderror"
                       value="{{ old('tahun', date('Y')) }}"
                       placeholder="Contoh: 2026">

                @error('tahun')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror

            </div>

            <div class="mt-3">

                <button type="submit"
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