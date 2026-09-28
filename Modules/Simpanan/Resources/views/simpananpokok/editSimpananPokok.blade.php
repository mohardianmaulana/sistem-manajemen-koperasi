@extends('adminlte::page')

@section('title', 'Edit Simpanan Pokok')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Simpanan Pokok</h1>
@stop

@section('content')

@php
    $isAdmin = auth()->check() && auth()->user()->hasRole('koordinator');
@endphp

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

        @role('anggota')
                    <div class="alert alert-info">

                    <h5>
                        <i class="fas fa-info-circle"></i>
                        Informasi Pengajuan Simpanan Sukarela
                    </h5>

                    <p class="mb-2">
                        Simpanan sukarela merupakan simpanan yang dapat disetorkan oleh anggota
                        kapan saja sesuai kemampuan anggota. Pengajuan akan diproses setelah
                        bendahara melakukan verifikasi terhadap bukti transfer.
                    </p>

                    <hr>

                    <h6 class="mb-2">
                        <i class="fas fa-university"></i>
                        Rekening Tujuan Transfer
                    </h6>

                    <table class="table table-borderless table-sm mb-2">

                        <tr>
                            <th width="180">Nama Bank</th>
                            <td>: Bank BRI</td>
                        </tr>

                        <tr>
                            <th>No. Rekening</th>
                            <td>: 1234567890</td>
                        </tr>

                        <tr>
                            <th>Atas Nama</th>
                            <td>: Koperasi Karyawan Politeknik Negeri Banyuwangi</td>
                        </tr>

                    </table>

                    <hr>

                    <strong>Langkah Pengajuan</strong>

                    <ol class="mb-0">

                        <li>Transfer sesuai nominal yang ingin disimpan.</li>

                        <li>Isi form pengajuan simpanan sukarela.</li>

                        <li>Unggah bukti transfer.</li>

                        <li>Koordinator Simpan Pinjam akan melakukan verifikasi.</li>

                        <li>Status pengajuan dapat dipantau pada halaman Simpanan Sukarela.</li>

                    </ol>

                </div>
                @endrole
        <div class="card">
            <div class="card-body">

                <h4>Form Update Simpanan</h4>

                <form action="{{ route('simpanan-pokok.update', $simpanan->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- NILAI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nilai</label>

                                <input type="number"
                                       name="nilai"
                                       class="form-control @error('nilai') is-invalid @enderror"
                                       value="{{ old('nilai', $simpanan->nilai) }}"
                                       {{ !$isAdmin ? 'readonly' : '' }}>

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
                                <label>Tanggal</label>

                                <input type="date"
                                       name="tanggal"
                                       class="form-control @error('tanggal') is-invalid @enderror"
                                       value="{{ old('tanggal', \Carbon\Carbon::parse($simpanan->tanggal)->format('Y-m-d')) }}"
                                       {{ !$isAdmin ? 'readonly' : '' }}>

                                @error('tanggal')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label>Status</label>

                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror"
                                        {{ !$isAdmin ? 'disabled' : '' }}>

                                    <option value="pending"
                                        {{ old('status', $simpanan->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="selesai"
                                        {{ old('status', $simpanan->status) == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>

                                    <option value="tidak berhasil"
                                        {{ old('status', $simpanan->status) == 'tidak berhasil' ? 'selected' : '' }}>
                                        Tidak Berhasil
                                    </option>

                                </select>

                                {{-- Agar nilai status tetap terkirim saat anggota melakukan submit --}}
                                @unless($isAdmin)
                                    <input type="hidden"
                                           name="status"
                                           value="{{ $simpanan->status }}">
                                @endunless

                                @error('status')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- BUKTI --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-group">

                                <label>Bukti Transfer</label>

                                @if($simpanan->bukti)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $simpanan->bukti) }}"
                                            width="150"
                                            style="border-radius:10px;">
                                    </div>
                                @endif

                                <input
                                    type="file"
                                    name="bukti"
                                    class="form-control @error('bukti') is-invalid @enderror"
                                    accept="image/*"
                                    {{ $isAdmin ? 'disabled' : '' }}>

                                @role('anggota')
                                    <small class="text-info d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        BUkti yang di dukung berupa
                                        <strong>Jpg, Png, Jpeg, Max 2mb</strong>    
                                    </small>
                                @endrole

                                @error('bukti')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
</div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-3">
                        <button type="submit"
                                class="btn btn-primary"
                                style="border-radius:10px;">
                            <i class="fas fa-save"></i>
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@stop