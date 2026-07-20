@extends('adminlte::page')

@section('title', 'Tambah Pengajuan Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Pengajuan Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if($errors->any())
                <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                <li>
                {{ $error }}
                </li>
                @endforeach
                </ul>
                </div>
            @endif
            <div class="mb-3">
                <a href="{{ route('pengajuanPinjaman.indexAnggota') }}" class="btn btn-secondary" style="border-radius: 10px;">
                    <i class="fa-solid fa-backward me-2"></i> Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 style="color: black;">Tambah pengajuan pinjaman</h4>
                    {{-- FORM --}}
                    <form action="{{ route('pengajuanPinjaman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- SKEMA PINJAMAN --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Skema Pinjaman</label>

                                    {{-- hidden input --}}
                                    <input type="hidden"
                                        name="id_skema_pinjaman"
                                        value="{{ $skema->id }}">

                                    {{-- tampil nama skema --}}
                                    <input type="text"
                                        class="form-control"
                                        value="{{ $skema->nama }}"
                                        readonly>
                                </div>
                            </div>
                            {{-- JUMLAH PENGAJUAN --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Pengajuan <span class="text-danger">*</span></label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Rp
                                            </span>
                                        </div>

                                        <input type="number"
                                            name="jumlah_pengajuan"
                                            class="form-control @error('jumlah_pengajuan') is-invalid @enderror"
                                            placeholder="Masukkan jumlah pengajuan dari Rp {{ number_format($skema->min_nominal, 0, ',', '.') }} - Rp {{ number_format($skema->max_nominal, 0, ',', '.') }}"
                                            value="{{ old('jumlah_pengajuan') }}">
                                    </div>

                                    @error('jumlah_pengajuan')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- LAMA ANGSURAN --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lama Angsuran (Bulan) <span class="text-danger">*</span></label>

                                    <input type="number"
                                        name="lama_angsuran"
                                        class="form-control @error('lama_angsuran') is-invalid @enderror"
                                        placeholder="Masukkan lama angsuran dari {{ number_format($skema->min_tenor, 0, ',', '.') }} - {{ number_format($skema->max_tenor, 0, ',', '.') }} bulan"
                                        value="{{ old('lama_angsuran') }}">

                                    @error('lama_angsuran')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- TANGGAL PENGAJUAN --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Pengajuan <span class="text-danger">*</span></label>

                                    <input type="date"
                                        name="tanggal_pengajuan"
                                        class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
                                        value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}">

                                    @error('tanggal_pengajuan')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- NO HP --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No HP <span class="text-danger">*</span></label>

                                    <input type="text"
                                        name="no_hp"
                                        class="form-control @error('no_hp') is-invalid @enderror"
                                        placeholder="Masukkan nomor HP"
                                        value="{{ old('no_hp') }}">

                                    @error('no_hp')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- NO KTP --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No KTP <span class="text-danger">*</span></label>

                                    <input type="text"
                                        name="no_ktp"
                                        class="form-control @error('no_ktp') is-invalid @enderror"
                                        placeholder="Masukkan nomor KTP"
                                        value="{{ old('no_ktp') }}">

                                    @error('no_ktp')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- NO REKENING --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Rekening <span class="text-danger">*</span></label>

                                    <input type="text"
                                        name="no_rekening"
                                        class="form-control @error('no_rekening') is-invalid @enderror"
                                        placeholder="Masukkan nomor rekening"
                                        value="{{ old('no_rekening') }}">

                                    @error('no_rekening')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- NAMA SUAMI / ISTRI --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Suami / Istri <span class="text-danger">*</span></label>

                                    <input type="text"
                                        name="nama_istri_suami"
                                        class="form-control @error('nama_istri_suami') is-invalid @enderror"
                                        placeholder="Masukkan nama suami / istri"
                                        value="{{ old('nama_istri_suami') }}">

                                    @error('nama_istri_suami')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat <span class="text-danger">*</span></label>

                                    <textarea name="alamat"
                                            rows="4"
                                            class="form-control @error('alamat') is-invalid @enderror"
                                            placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>

                                    @error('alamat')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- JAMINAN --}}
                            @if($skema->jaminan == "ada" && $skema->daftarJaminan->count() > 0)
                            <div class="col-12 mt-3">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">
                                            Dokumen Jaminan
                                        </h5>
                                    </div>

                                    <div class="card-body">

                                        <p class="text-danger">
                                            Skema pinjaman ini membutuhkan jaminan.
                                            Silahkan upload dokumen berikut:
                                        </p>

                                        <div class="row">
                                            @foreach($skema->daftarJaminan as $jaminan)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        {{ $jaminan->nama }}
                                                        <span class="text-danger">
                                                            *
                                                        </span>
                                                    </label>
                                                    <input type="hidden"
                                                        name="jaminan[{{$loop->index}}][id_jaminan]"
                                                        value="{{ $jaminan->id }}">
                                                    <input type="file"
                                                        name="jaminan[{{$loop->index}}][file]"
                                                        class="form-control
                                                        @error('jaminan.'.$loop->index.'.file')
                                                        is-invalid
                                                        @enderror">
                                                    @error('jaminan.'.$loop->index.'.file')
                                                        <span class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-3">
                            <button type="submit"
                                    class="btn btn-primary"
                                    style="border-radius: 10px;">

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