@extends('adminlte::page')

@section('title', 'Edit Simpanan Wajib')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Simpanan Wajib</h1>
@stop

@section('content')

<div class="row">
<div class="col-12">

    <div class="mb-3">
        <a href="{{ route('simpanan-wajib.index') }}"
           class="btn btn-secondary"
           style="border-radius:10px">

            <i class="fas fa-arrow-left"></i>
            Kembali

        </a>
    </div>

    <div class="card">
    <div class="card-body">

        <h4>Form Update Simpanan Wajib</h4>

        <form
            action="{{ route('simpanan-wajib.update', $simpanan->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="row">

                {{-- NILAI --}}
                <div class="col-md-6">

                    <div class="form-group">

                        <label>Nilai</label>

                        <input
                            type="number"
                            name="nilai"
                            class="form-control @error('nilai') is-invalid @enderror"
                            value="{{ old('nilai', $simpanan->nilai) }}"
                            @role('anggota') readonly @endrole>

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

                        <input
                            type="date"
                            name="periode"
                            class="form-control @error('periode') is-invalid @enderror"
                            value="{{ old('periode', $simpanan->periode) }}"
                            @role('anggota') readonly @endrole>

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

                        <input
                            type="number"
                            name="tahun"
                            class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', $simpanan->tahun) }}"
                            @role('anggota') readonly @endrole>

                        @error('tahun')
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

                        <select
                            name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            @role('anggota') disabled @endrole>

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

                        {{-- Agar status tetap dikirim saat anggota submit --}}
                        @role('anggota')
                            <input
                                type="hidden"
                                name="status"
                                value="{{ $simpanan->status }}">
                        @endrole

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
                                        <a href="{{ asset('storage/'.$simpanan->bukti) }}"
                                        target="_blank"
                                        class="btn btn-info btn-sm">
                                            <i class="fas fa-image"></i>
                                            Lihat Bukti
                                        </a>
                                    </div>
                                @endif

                                <input
                                    type="file"
                                    name="bukti"
                                    class="form-control @error('bukti') is-invalid @enderror"
                                    accept="image/*"
                                    @role('anggota')
                                        {{ $simpanan->status != 'tidak berhasil' ? 'disabled' : '' }}
                                    @endrole
                                >

                                @role('anggota')
                                    @if($simpanan->status != 'tidak berhasil')
                                        <small class="text-danger">
                                            Bukti transfer hanya dapat diunggah apabila status pengajuan
                                            <strong>Tidak Berhasil</strong>.
                                        </small>
                                    @else
                                        <small class="text-primary">
                                            Silakan transfer secara mandiri ke rekening
                                            <strong>981237981237</strong>, kemudian unggah bukti transfer pada form di atas.
                                        </small>
                                    @endif
                                @endrole

                                @error('bukti')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                        </div>

                    </div>

            <div class="mt-3">

                <button
                    type="submit"
                    class="btn btn-primary"
                    style="border-radius:10px">

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