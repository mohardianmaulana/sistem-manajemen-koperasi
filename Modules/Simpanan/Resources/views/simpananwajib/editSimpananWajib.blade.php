@extends('adminlte::page')

@section('title', 'Edit Simpanan Sukarela')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Simpanan Sukarela</h1>
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

                <h4>Form Update Simpanan Wajib</h4>

                <form action="{{ route('simpanan-wajib.update', $simpanan->id) }}"
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
                                       value="{{ old('nilai', $simpanan->nilai) }}">

                                @error('nilai')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- PERIODE --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Periode</label>

                                <input type="date"
                                       name="periode"
                                       class="form-control @error('periode') is-invalid @enderror"
                                       value="{{ old('periode', $simpanan->periode) }}">

                                @error('periode')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- TAHUN --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label>Tahun</label>

                                <input type="number"
                                       name="tahun"
                                       class="form-control @error('tahun') is-invalid @enderror"
                                       value="{{ old('tahun', $simpanan->tahun) }}">

                                @error('tahun')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- STATUS (PENGURUS NANTI YANG PAKAI) --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label>Status</label>

                                <select name="status"
                                        class="form-control @error('status') is-invalid @enderror">

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

                                @error('status')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- BUKTI (PENGURUS) --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label>Bukti</label>

                                @if($simpanan->bukti)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $simpanan->bukti) }}"
                                           target="_blank">
                                            Lihat Bukti Lama
                                        </a>
                                    </div>
                                @endif

                                <input type="file"
                                       name="bukti"
                                       class="form-control @error('bukti') is-invalid @enderror">

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