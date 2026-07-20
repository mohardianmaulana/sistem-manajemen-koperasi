@extends('adminlte::page')

@section('title', 'Tambah Jaminan')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Jaminan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <a href="{{ route('jaminan.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                    <i class="fa-solid fa-backward me-2"></i> Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 style="color: black;">Tambah jaminan</h4>
                    {{-- FORM --}}
                    <form action="{{ route('jaminan.store') }}" method="POST">
                        @csrf
                        <div class="column">
                            {{-- Nama Skema --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama, seperti : sertifikat tanah" value="{{ old('nama') }}">
                                    @error('nama')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi"
                                        class="form-control @error('deskripsi') is-invalid @enderror"
                                        rows="4"
                                        required
                                        placeholder="Masukkan deskripsi">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        {{-- BUTTON --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')

@endpush