```blade
@extends('adminlte::page')

@section('title', 'Upload Bukti Transfer')

@section('content_header')
    <h1>Upload Bukti Transfer</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        {{-- Informasi Pembayaran --}}
        <div class="alert alert-info">

            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Informasi Pembayaran</strong>
            </div>

            <hr class="my-2">

            <p class="mb-2">
                Silakan melakukan <strong>transfer secara manual</strong>
                ke rekening berikut:
            </p>

            <table class="table table-borderless table-sm mb-2">

                <tr>
                    <td width="140">
                        <strong>Bank</strong>
                    </td>
                    <td>
                        : BRI
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>No. Rekening</strong>
                    </td>
                    <td>
                        : 981237981237
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Atas Nama</strong>
                    </td>
                    <td>
                        : Koperasi Karyawan Politeknik Negeri Banyuwangi
                    </td>
                </tr>

            </table>

            <p class="mb-0">
                Setelah melakukan transfer, silakan unggah bukti pembayaran
                pada form di bawah ini untuk dilakukan proses verifikasi
                oleh pengurus.
            </p>

        </div>


        {{-- Form Upload --}}
        <form
            action="{{ route('simpanan-sukarela.upload-bukti.store', $simpanan->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')


            {{-- Data Simpanan --}}
            <h5 class="mb-3">
                <i class="fas fa-wallet mr-1"></i>
                Data Simpanan
            </h5>

            <div class="form-group">

                <label>Nominal Simpanan</label>

                <input
                    type="text"
                    class="form-control"
                    value="Rp {{ number_format($simpanan->nilai, 0, ',', '.') }}"
                    readonly>

            </div>


            <div class="form-group">

                <label>Periode</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ \Carbon\Carbon::parse($simpanan->periode)->translatedFormat('F Y') }}"
                    readonly>

            </div>


            <div class="form-group">

                <label>Status</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ ucfirst($simpanan->status) }}"
                    readonly>

            </div>


            {{-- Bukti Transfer Sebelumnya --}}
            @if($simpanan->bukti)

                <div class="form-group">

                    <label>Bukti Transfer Sebelumnya</label>

                    <div>
                        <a
                            href="{{ asset('storage/' . $simpanan->bukti) }}"
                            target="_blank"
                            class="btn btn-info btn-sm">

                            <i class="fas fa-file-image mr-1"></i>
                            Lihat Bukti Transfer

                        </a>
                    </div>

                </div>

            @endif


            <hr>


            {{-- Upload Bukti Transfer --}}
            <h5 class="mb-3">
                <i class="fas fa-upload mr-1"></i>
                Upload Bukti Transfer
            </h5>

            <div class="form-group">

                <label>
                    Bukti Transfer
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="file"
                    name="bukti"
                    class="form-control @error('bukti') is-invalid @enderror"
                    accept="image/jpeg,image/png,application/pdf">

                <small class="text-muted">
                    Format yang diperbolehkan: JPG, JPEG, PNG, atau PDF.
                </small>

                @error('bukti')

                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            {{-- Tombol --}}
            <div class="d-flex justify-content-end mt-4">

                <a
                    href="{{ url()->previous() }}"
                    class="btn btn-secondary mr-2">

                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fas fa-upload mr-1"></i>
                    Upload Bukti Transfer

                </button>

            </div>

        </form>

    </div>

</div>

@stop