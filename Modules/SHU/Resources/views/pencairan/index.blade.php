@extends('adminlte::page')

@section('title', 'Pencairan SHU')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>

                    <i class="fas fa-money-check-alt"></i>

                    Penyaluran SHU

                </h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">

                        <a href="{{ route('home.index') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Penyaluran SHU

                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

        {{-- Flash Message --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible">

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    &times;

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

                    &times;

                </button>

                {{ session('error') }}

            </div>

        @endif


        {{-- ===================== bendahara ===================== --}}

        @role('bendahara')

            @include('shu::pencairan.partials.dashboard-admin')

            @include('shu::pencairan.partials.filter-admin')

            @include('shu::pencairan.partials.table-admin')

        @endrole


        {{-- ===================== ANGGOTA ===================== --}}

        @role('anggota')

            @include('shu::pencairan.partials.dashboard-anggota')

            @include('shu::pencairan.partials.table-anggota')

        @endrole

    </div>

</section>


@role('bendahara')

    @include('shu::pencairan.partials.modal-cairkan')

    @include('shu::pencairan.partials.modal-gagal')

@endrole

@endsection

@section('js')
<script>

$(document).ready(function () {

    $('#modalCairkan').on('show.bs.modal', function (event) {

        let button = $(event.relatedTarget);

        let id = button.data('id');
        let kode = button.data('kode');
        let nama = button.data('nama');
        let nominal = button.data('nominal');
        let status = button.data('status');
        let noRek = button.data('no_rek');

        // Ubah status menjadi lebih mudah dibaca
        let statusText = '';

        switch (status) {
            case 'siap_dicairkan':
                statusText = 'Siap Dicairkan';
                break;
            case 'dicairkan':
                statusText = 'Dicairkan';
                break;
            case 'gagal':
                statusText = 'Gagal';
                break;
            default:
                statusText = '-';
        }

        $('#kode_pencairan').val(kode);
        $('#nama_anggota').val(nama);
        $('#nominal_pencairan').val('Rp ' + nominal);
        $('#status_pencairan').val(statusText);
        $('#no_rekening').val(noRek);

        $('#formCairkan').attr(
            'action',
            "{{ url('pencairan') }}/" + id + "/cairkan"
        );

    });


    $('#modalGagal').on('show.bs.modal', function (event) {

        let button = $(event.relatedTarget);

        let id = button.data('id');
        let kode = button.data('kode');
        let nama = button.data('nama');
        let nominal = button.data('nominal');

        $('#gagal_kode_pencairan').val(kode);
        $('#gagal_nama_anggota').val(nama);
        $('#gagal_nominal').val('Rp ' + nominal);

        $('#formGagal').attr(
            'action',
            "{{ url('pencairan') }}/" + id + "/gagal"
        );

    });

});

</script>
@endsection