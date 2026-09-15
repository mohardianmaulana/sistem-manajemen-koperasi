@extends('adminlte::page')

@section('title', 'Tambah Simpanan Sukarela')

@section('content_header')
    <h1 class="m-0 text-dark">
        Tambah Simpanan Sukarela
    </h1>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">

        {{-- Tombol Kembali --}}
        <div class="mb-3">
            <a href="{{ route('simpanan-sukarela.index') }}"
               class="btn btn-secondary"
               style="border-radius: 10px">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali

            </a>
        </div>

        @role('anggota')
        {{-- Informasi Pengajuan --}}
        <div class="alert alert-info">

            <h5>
                <i class="fas fa-info-circle mr-1"></i>
                Informasi Pengajuan Simpanan Sukarela
            </h5>

            <p class="mb-2">
                Simpanan sukarela merupakan simpanan yang dapat disetorkan
                oleh anggota kapan saja sesuai kemampuan anggota.
                Pengajuan akan diproses setelah pengurus melakukan verifikasi
                terhadap data dan bukti transfer.
            </p>

            <hr>

            <h6 class="mb-2">
                <i class="fas fa-university mr-1"></i>
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
                    <td>
                        : Koperasi Karyawan Politeknik Negeri Banyuwangi
                    </td>
                </tr>

            </table>

            <hr>

            <strong>Langkah Pengajuan</strong>

            <ol class="mb-0 mt-2">
                <li>Transfer sesuai nominal yang ingin disimpan.</li>
                <li>Isi form pengajuan simpanan sukarela.</li>
                <li>Unggah bukti transfer.</li>
                <li>Pengurus melakukan verifikasi pengajuan.</li>
                <li>Status pengajuan dapat dipantau pada halaman Simpanan Sukarela.</li>
            </ol>

        </div>
        @endrole
        
        {{-- Form --}}
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-wallet mr-1"></i>
                    Form Pengajuan Simpanan Sukarela
                </h3>
            </div>


            <form
                action="{{ route('simpanan-sukarela.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="card-body">


               @role('koordinator')

                <div class="form-group">
                    <label for="id_anggota">
                        Anggota
                    </label>

                    <select
                        name="id_anggota"
                        id="id_anggota"
                        class="form-control select2 @error('id_anggota') is-invalid @enderror"
                        style="width: 100%;">

                        <option value="">
                            -- Gunakan akun yang sedang login --
                        </option>

                        @foreach($users as $user)
                            <option
                                value="{{ $user->id }}"
                                {{ old('id_anggota') == $user->id ? 'selected' : '' }}>

                                {{ $user->nip }} - {{ $user->name }}

                            </option>
                        @endforeach

                    </select>

                    <small class="text-muted">
                        Kosongkan jika simpanan dicatat untuk akun yang sedang login.
                    </small>

                    @error('id_anggota')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                @endrole

                    {{-- Nominal dan Periode --}}
                    <div class="row">

                        {{-- Nominal --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Nominal Simpanan
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            Rp
                                        </span>
                                    </div>

                                    <input
                                        type="number"
                                        name="nilai"
                                        value="{{ old('nilai') }}"
                                        class="form-control @error('nilai') is-invalid @enderror"
                                        placeholder="Masukkan nominal simpanan"
                                        min="1">

                                </div>

                                @error('nilai')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Periode --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>
                                    Berlaku Mulai
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="periode"
                                    value="{{ old('periode') }}"
                                    class="form-control @error('periode') is-invalid @enderror">

                                @error('periode')
                                    <span class="invalid-feedback d-block">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Tahun --}}
                    <div class="form-group">

                        <label>
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="tahun"
                            class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', date('Y')) }}"
                            readonly>

                        @error('tahun')
                            <span class="invalid-feedback d-block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Upload Bukti Transfer --}}
                    @role('anggota')

                        <div class="form-group">

                            <label>
                                Bukti Transfer
                            </label>

                            <div class="custom-file">

                                <input
                                    type="file"
                                    id="bukti"
                                    name="bukti"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="custom-file-input @error('bukti') is-invalid @enderror">

                                <label
                                    class="custom-file-label"
                                    for="bukti">

                                    Pilih bukti transfer...

                                </label>

                            </div>

                            <small class="text-muted">
                                Format file yang diperbolehkan:
                                JPG, JPEG, PNG atau PDF.
                                Maksimal ukuran file 2 MB.
                            </small>

                            @error('bukti')
                                <span class="invalid-feedback d-block">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    @endrole


                    {{-- Perhatian --}}
                    <div class="alert alert-warning mb-0">

                        <h6>
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Perhatian
                        </h6>

                        <ul class="mb-0">

                            <li>
                                Pastikan nominal transfer sesuai dengan
                                nominal yang diajukan.
                            </li>

                            <li>
                                Pastikan bukti transfer terlihat jelas
                                dan tidak terpotong.
                            </li>

                            <li>
                                Pengajuan akan diverifikasi terlebih dahulu
                                oleh pengurus.
                            </li>

                        </ul>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="card-footer">

                    <button
                        type="submit"
                        class="btn btn-primary"
                        style="border-radius: 10px">

                        <i class="fas fa-save mr-1"></i>
                        Ajukan Simpanan Sukarela

                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

@stop


@section('js')

<script>
$(document).ready(function () {

    $('#id_anggota').select2({
        theme: 'bootstrap4',
        placeholder: 'Cari anggota...',
        allowClear: true,
        width: '100%'
    });

});
</script>

@stop
