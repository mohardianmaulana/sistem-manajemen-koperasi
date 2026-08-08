@extends('adminlte::page')

@section('title', 'Pencairan Simpanan Sukarela')

@section('content')

<section class="content-header">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible">

            <button
                type="button"
                class="close"
                data-dismiss="alert">

                <span>&times;</span>

            </button>

            {{ session('success') }}

        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible">

            <button
                type="button"
                class="close"
                data-dismiss="alert">

                <span>&times;</span>

            </button>

            {{ session('error') }}

        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible">

            <button
                type="button"
                class="close"
                data-dismiss="alert">

                <span>&times;</span>

            </button>

            <strong>Terjadi kesalahan:</strong>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
        @endif

        <div class="row mb-2">

            <div class="col-sm-6">
                <h1>Pencairan Simpanan Sukarela</h1>
            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">
                        <a href="{{ route('home.index') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Pencairan Simpanan
                    </li>

                </ol>

            </div>

        </div>

    </div>
</section>

<section class="content">

    <div class="container-fluid">

        @role('anggota')
            @include('simpanan::pencairan.partials.card-anggota')
        @endrole

        @role('koordinator')
            @include('simpanan::pencairan.partials.card-koordinator')
        @endrole

        @role('bendahara')
            @include('simpanan::pencairan.partials.card-bendahara')
        @endrole

        @include('simpanan::pencairan.partials.table')

    </div>

    @include('simpanan::pencairan.partials.modal-verifikasi')
    @include('simpanan::pencairan.partials.modal-tolak')
    @include('simpanan::pencairan.partials.modal-cairkan')
    @include('simpanan::pencairan.partials.modal-gagal')

</section>

@endsection

@section('js')

<script>

$(function () {

    $('.btn-verifikasi').on('click', function () {

        const id = $(this).data('id');

        $('#formVerifikasi').attr(
            'action',
            "{{ url('pencairan-simpanan') }}/" + id + "/verifikasi"
        );

    });

    $('.btn-tolak').on('click', function () {

        const id = $(this).data('id');

        $('#formTolak').attr(
            'action',
            "{{ url('pencairan-simpanan') }}/" + id + "/tolak"
        );

        $('#tolakKode').text($(this).data('kode'));
        $('#tolakNama').text($(this).data('nama'));
        $('#tolakNominal').text(
            "Rp " + $(this).data('nominal')
        );

    });

        $('.btn-cairkan').on('click', function () {

        const id = $(this).data('id');

        $('#formCairkan').attr(
            'action',
            "{{ url('pencairan-simpanan') }}/" + id + "/cairkan"
        );

        $('#modalKode').text($(this).data('kode'));
        $('#modalNama').text($(this).data('nama'));
        $('#modalRekening').text($(this).data('rekening'));
        $('#modalNominal').text(
            "Rp " + $(this).data('nominal')
        );

    });

        $('.btn-gagal').on('click', function () {

        const id = $(this).data('id');

        $('#formGagal').attr(
            'action',
            "{{ url('pencairan-simpanan') }}/" + id + "/gagal"
        );

        $('#gagalKode').text($(this).data('kode'));
        $('#gagalNama').text($(this).data('nama'));
        $('#gagalNominal').text(
            "Rp " + $(this).data('nominal')
        );

    });

});

</script>

@endsection