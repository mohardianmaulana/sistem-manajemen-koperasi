@extends('adminlte::page')

@section('title', 'Pencairan SHU')

@section('content_header')

<h1 class="m-0 text-dark">

    <i class="fas fa-money-check-alt text-success"></i>

    Pencairan SHU

</h1>

@stop

@section('content')

<div class="container-fluid">

{{-- ===========================
        ALERT
============================ --}}

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <i class="fas fa-check-circle"></i>

    {{ session('success') }}

</div>

@endif


@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <i class="fas fa-times-circle"></i>

    {{ session('error') }}

</div>

@endif


@if($errors->any())

<div class="alert alert-danger alert-dismissible fade show">

    <button
        type="button"
        class="close"
        data-dismiss="alert">

        &times;

    </button>

    <ul class="mb-0">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


{{-- ===========================
        DASHBOARD
============================ --}}

@role('admin')

<div class="row">

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h3>

                    {{ $dashboard['total_pengajuan'] ?? 0 }}

                </h3>

                <p>

                    Total Pengajuan

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-file-alt"></i>

            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>

                    {{ $dashboard['menunggu'] ?? 0 }}

                </h3>

                <p>

                    Menunggu

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-clock"></i>

            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>

                    {{ $dashboard['disetujui'] ?? 0 }}

                </h3>

                <p>

                    Disetujui

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-check-circle"></i>

            </div>

        </div>

    </div>


    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>

                    {{ $dashboard['dicairkan'] ?? 0 }}

                </h3>

                <p>

                    Dicairkan

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-wallet"></i>

            </div>

        </div>

    </div>

</div>

@endrole



@role('anggota')

<div class="row">

    <div class="col-lg-4">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>

                    Rp {{ number_format($summary['total_shu'] ?? 0,0,',','.') }}

                </h3>

                <p>

                    Total SHU

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-coins"></i>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>

                    Rp {{ number_format($summary['total_dicairkan'] ?? 0,0,',','.') }}

                </h3>

                <p>

                    Sudah Dicairkan

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-money-check"></i>

            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>

                    Rp {{ number_format($summary['sisa_shu'] ?? 0,0,',','.') }}

                </h3>

                <p>

                    Sisa SHU

                </p>

            </div>

            <div class="icon">

                <i class="fas fa-piggy-bank"></i>

            </div>

        </div>

    </div>

</div>

@endrole


{{-- ===========================
        FILTER
============================ --}}

<div class="card shadow-sm mb-3">

    <div class="card-header bg-primary">

        <h3 class="card-title text-white">

            <i class="fas fa-filter"></i>

            Filter Pengajuan

        </h3>

    </div>

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('pencairan.index') }}">

            <div class="row align-items-end">

                <div class="col-md-4">

                    <label>Status</label>

                    <select
                        name="status"
                        class="form-control">

                        <option value="">

                            Semua Status

                        </option>

                        <option
                            value="menunggu"
                            {{ request('status')=='menunggu' ? 'selected' : '' }}>

                            Menunggu

                        </option>

                        <option
                            value="disetujui"
                            {{ request('status')=='disetujui' ? 'selected' : '' }}>

                            Disetujui

                        </option>

                        <option
                            value="dicairkan"
                            {{ request('status')=='dicairkan' ? 'selected' : '' }}>

                            Dicairkan

                        </option>

                        <option
                            value="ditolak"
                            {{ request('status')=='ditolak' ? 'selected' : '' }}>

                            Ditolak

                        </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button
                        class="btn btn-primary btn-block">

                        <i class="fas fa-search"></i>

                        Filter

                    </button>

                </div>

                <div class="col-md-2">

                    <a
                        href="{{ route('pencairan.index') }}"
                        class="btn btn-secondary btn-block">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ===========================
        TOMBOL
============================ --}}

<div class="card shadow">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-table"></i>

            Data Pengajuan Pencairan SHU

        </h3>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="bg-light text-center">

                    <tr>

                        <th width="60">No</th>

                        @role('admin')
                        <th>Nama Anggota</th>
                        <th>Periode SHU</th>
                        @endrole

                        <th>Nominal Pengajuan</th>

                        <th>Tanggal Pengajuan</th>

                        <th>Status</th>

                        <th width="220">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($data as $item)

                    <tr>

                        <td class="text-center">

                            {{ $data->firstItem() + $loop->index }}

                        </td>

                        @role('admin')

                        <td>

                            {{ optional($item->shuAnggota->user)->name }}

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_awal)->format('d M Y') }}

                            <br>

                            <small class="text-muted">

                                s/d

                            </small>

                            <br>

                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_akhir)->format('d M Y') }}

                        </td>

                        @endrole

                        <td>

                            <strong>

                                Rp {{ number_format($item->nominal_pengajuan,0,',','.') }}

                            </strong>

                        </td>

                        <td class="text-center">

                            {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}

                        </td>

                        <td class="text-center">

                            {!! $item->status_badge !!}

                        </td>

                        <td class="text-center">

                            {{-- Detail --}}
                            <a
                                href="{{ route('pencairan.show',$item->id) }}"
                                class="btn btn-info btn-sm">

                                <i class="fas fa-eye"></i>

                            </a>

                            @role('admin')

                                @if($item->status == \Modules\SHU\Entities\PencairanShu::STATUS_MENUNGGU)

                                    <button
                                        class="btn btn-success btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalApprove{{ $item->id }}">

                                        <i class="fas fa-check"></i>

                                    </button>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalReject{{ $item->id }}">

                                        <i class="fas fa-times"></i>

                                    </button>

                                @endif

                                @if($item->status == \Modules\SHU\Entities\PencairanShu::STATUS_DISETUJUI)

                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#modalCairkan{{ $item->id }}">

                                        <i class="fas fa-money-check-alt"></i>

                                    </button>

                                @endif

                            @endrole

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center text-muted">

                            <i class="fas fa-folder-open"></i>

                            Belum ada data pengajuan pencairan SHU.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3 d-flex justify-content-end">

            {{ $data->links() }}

        </div>

    </div>

</div>
{{-- ==========================================
            MODAL
========================================== --}}

@foreach($data as $item)

{{-- ==========================================
        MODAL APPROVE
========================================== --}}

<div class="modal fade"
     id="modalApprove{{ $item->id }}"
     tabindex="-1">

    <div class="modal-dialog">

        <form
            action="{{ route('pencairan.approve',$item->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-success">

                    <h5 class="modal-title text-white">

                        Approve Pengajuan

                    </h5>

                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <table class="table table-sm table-bordered">

                        <tr>

                            <th width="40%">Nama Anggota</th>

                            <td>

                                {{ optional($item->shuAnggota->user)->name }}

                            </td>

                        </tr>

                        <tr>

                            <th>Nominal</th>

                            <td>

                                Rp {{ number_format($item->nominal_pengajuan,0,',','.') }}

                            </td>

                        </tr>

                    </table>

                    <div class="alert alert-info mb-0">

                        Apakah Anda yakin ingin menyetujui pengajuan ini?

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Approve

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ==========================================
        MODAL REJECT
========================================== --}}

<div class="modal fade"
     id="modalReject{{ $item->id }}"
     tabindex="-1">

    <div class="modal-dialog">

        <form
            action="{{ route('pencairan.reject',$item->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-danger">

                    <h5 class="modal-title text-white">

                        Tolak Pengajuan

                    </h5>

                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>

                            Alasan Penolakan

                        </label>

                        <textarea
                            name="keterangan"
                            rows="4"
                            class="form-control"
                            required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-danger">

                        Tolak

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ==========================================
        MODAL CAIRKAN
========================================== --}}

<div class="modal fade"
     id="modalCairkan{{ $item->id }}"
     tabindex="-1">

    <div class="modal-dialog">

        <form
            action="{{ route('pencairan.cairkan',$item->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-primary">

                    <h5 class="modal-title text-white">

                        Pencairan SHU

                    </h5>

                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>

                            Nama Anggota

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ optional($item->shuAnggota->user)->name }}"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>

                            Nominal

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Rp {{ number_format($item->nominal_pengajuan,0,',','.') }}"
                            readonly>

                    </div>

                    <div class="form-group">

                        <label>

                            Upload Bukti Transfer

                        </label>

                        <input
                            type="file"
                            class="form-control"
                            name="bukti"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-primary">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



</div>

{{-- ==========================================
        MODAL DETAIL
========================================== --}}

<div class="modal fade"
     id="modalDetail{{ $item->id }}"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-info">

                <h5 class="modal-title text-white">

                    Detail Pengajuan

                </h5>

                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>

                        <th width="35%">Nama Anggota</th>

                        <td>

                            {{ optional($item->shuAnggota->user)->name }}

                        </td>

                    </tr>

                    <tr>

                        <th>Periode</th>

                        <td>

                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_awal)->format('d M Y') }}

                            -

                            {{ \Carbon\Carbon::parse($item->shuAnggota->periode_akhir)->format('d M Y') }}

                        </td>

                    </tr>

                    <tr>

                        <th>Nominal Pengajuan</th>

                        <td>

                            Rp {{ number_format($item->nominal_pengajuan,0,',','.') }}

                        </td>

                    </tr>

                    <tr>

                        <th>Status</th>

                        <td>

                            {!! $item->status_badge !!}

                        </td>

                    </tr>

                    @if($item->keterangan)

                    <tr>

                        <th>Keterangan</th>

                        <td>

                            {{ $item->keterangan }}

                        </td>

                    </tr>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endforeach
{{-- ==========================================
            JAVASCRIPT
========================================== --}}

@push('js')

<script>

$(function () {

    /*
    |--------------------------------------------------------------------------
    | Auto Close Alert
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        $('.alert').fadeOut('slow');

    }, 4000);


    /*
    |--------------------------------------------------------------------------
    | Reset Form Saat Modal Ditutup
    |--------------------------------------------------------------------------
    */

    $('.modal').on('hidden.bs.modal', function () {

        let form = $(this).find('form');

        if (form.length) {

            form[0].reset();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Approve
    |--------------------------------------------------------------------------
    */

    $('form[action*="approve"]').submit(function () {

        return confirm(
            'Apakah Anda yakin ingin menyetujui pengajuan pencairan SHU ini?'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Reject
    |--------------------------------------------------------------------------
    */

    $('form[action*="reject"]').submit(function () {

        return confirm(
            'Apakah Anda yakin ingin menolak pengajuan ini?'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Cairkan
    |--------------------------------------------------------------------------
    */

    $('form[action*="cairkan"]').submit(function () {

        return confirm(
            'Pastikan dana sudah ditransfer. Lanjutkan pencairan SHU?'
        );

    });



    $('input[type=file]').change(function () {

        let fileName = $(this).val().split('\\').pop();

        if (fileName !== '') {

            $(this).next('.custom-file-label').html(fileName);

        }

    });

});

</script>

@endpush
@endsection