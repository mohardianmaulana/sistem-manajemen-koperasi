@extends('adminlte::page')

@section('title','Upload Bukti Transfer')

@section('content_header')
<h1>Upload Bukti Transfer</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <form
            action="{{ route('simpanan-sukarela.upload-bukti.store',$simpanan->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nominal Simpanan</label>

                <input
                    type="text"
                    class="form-control"
                    value="Rp {{ number_format($simpanan->nilai,0,',','.') }}"
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

         @if($simpanan->bukti)

    <div class="mb-3">

        <a
            href="{{ asset('storage/'.$simpanan->bukti) }}"
            target="_blank"
            class="btn btn-info btn-sm">

            <i class="fas fa-image"></i>

            Lihat Bukti Transfer

        </a>

    </div>

@endif

<div class="alert alert-info">

    <i class="fas fa-info-circle"></i>

    <strong>Informasi Pembayaran</strong>

    <hr class="my-2">

    Silakan melakukan <strong>transfer secara manual</strong> ke rekening berikut:

    <br><br>

    <table class="table table-borderless table-sm mb-2">

        <tr>
            <td width="120"><strong>Bank</strong></td>
            <td>: BRI</td>
        </tr>

        <tr>
            <td><strong>No. Rekening</strong></td>
            <td>: 981237981237</td>
        </tr>

        <tr>
            <td><strong>Atas Nama</strong></td>
            <td>: Koperasi Karyawan Politeknik Negeri Banyuwangi</td>
        </tr>

    </table>

    Setelah melakukan transfer, silakan unggah bukti pembayaran pada form di bawah ini untuk dilakukan proses verifikasi oleh pengurus.

</div>

<div class="form-group">

    <label>Bukti Transfer <span class="text-danger">*</span></label>

    <input
        type="file"
        name="bukti"
        class="form-control @error('bukti') is-invalid @enderror"
        accept="image/*,.pdf">

    <small class="text-muted">

        Format yang diperbolehkan: JPG, JPEG, PNG .

    </small>

    @error('bukti')

        <span class="invalid-feedback d-block">

            {{ $message }}

        </span>

    @enderror

</div>

<div class="text-right">

    <button
        type="submit"
        class="btn btn-primary">

        <i class="fas fa-upload"></i>

        Upload Bukti Transfer

    </button>

</div>

        </form>

    </div>

</div>

@stop