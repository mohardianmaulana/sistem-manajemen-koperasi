@extends('adminlte::page')

@section('title', 'Detail Pencairan SHU')

@section('content_header')

<h1 class="m-0 text-dark">

    <i class="fas fa-money-check-alt text-success"></i>

    Detail Pencairan SHU

</h1>

@stop

@section('content')

<div class="container-fluid">

{{-- ==========================================
        ALERT
========================================== --}}

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


{{-- ==========================================
        CARD DETAIL
========================================== --}}

<div class="card shadow">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h3 class="card-title">

                <i class="fas fa-info-circle"></i>

                Detail Pengajuan Pencairan SHU

            </h3>

            <a
                href="{{ route('pencairan.index') }}"
                class="btn btn-secondary btn-sm">

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="row">

            {{-- ===============================
                    DATA ANGGOTA
            ================================ --}}

            <div class="col-md-6">

                <div class="card card-outline card-primary">

                    <div class="card-header">

                        <h3 class="card-title">

                            <i class="fas fa-user"></i>

                            Data Anggota

                        </h3>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-bordered mb-0">

                            <tr>

                                <th width="35%">

                                    Nama

                                </th>

                                <td>

                                    {{ $data->shuAnggota->user->name }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    NIP

                                </th>

                                <td>

                                    {{ $data->shuAnggota->user->nip }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Periode SHU

                                </th>

                                <td>

                                    {{ \Carbon\Carbon::parse($data->shuAnggota->periode_awal)->translatedFormat('d F Y') }}

                                    <br>

                                    <small class="text-muted">

                                        s/d

                                    </small>

                                    <br>

                                    {{ \Carbon\Carbon::parse($data->shuAnggota->periode_akhir)->translatedFormat('d F Y') }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ===============================
                    INFORMASI SHU
            ================================ --}}

            <div class="col-md-6">

                <div class="card card-outline card-success">

                    <div class="card-header">

                        <h3 class="card-title">

                            <i class="fas fa-wallet"></i>

                            Informasi SHU

                        </h3>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-bordered mb-0">

                            <tr>

                                <th width="40%">

                                    SHU Simpanan

                                </th>

                                <td>

                                    Rp {{ number_format($data->shuAnggota->shu_simpanan,0,',','.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    SHU Pinjaman

                                </th>

                                <td>

                                    Rp {{ number_format($data->shuAnggota->shu_pinjaman,0,',','.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Pajak

                                </th>

                                <td>

                                    Rp {{ number_format($data->shuAnggota->pajak,0,',','.') }}

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Total SHU

                                </th>

                                <td>

                                    <span class="badge badge-success p-2">

                                        Rp {{ number_format($data->shuAnggota->shu_anggota,0,',','.') }}

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <th>

                                    Nominal Pengajuan

                                </th>

                                <td>

                                    <span class="badge badge-primary p-2">

                                        Rp {{ number_format($data->nominal_pengajuan,0,',','.') }}

                                    </span>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <hr>

<div class="card card-outline card-warning">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-exchange-alt"></i>

            Status Pencairan SHU

        </h3>

    </div>

    <div class="card-body p-0">

        <table class="table table-bordered mb-0">

            <tr>

                <th width="25%">

                    Status

                </th>

                <td>

                    @switch($data->status)

                        @case('menunggu')

                            <span class="badge badge-warning p-2">

                                <i class="fas fa-clock"></i>

                                Menunggu Persetujuan

                            </span>

                        @break

                        @case('disetujui')

                            <span class="badge badge-primary p-2">

                                <i class="fas fa-check"></i>

                                Disetujui

                            </span>

                        @break

                        @case('ditolak')

                            <span class="badge badge-danger p-2">

                                <i class="fas fa-times"></i>

                                Ditolak

                            </span>

                        @break

                        @case('dicairkan')

                            <span class="badge badge-success p-2">

                                <i class="fas fa-check-circle"></i>

                                Dicairkan

                            </span>

                        @break

                    @endswitch

                </td>

            </tr>

            <tr>

                <th>

                    Nominal Dicairkan

                </th>

                <td>

                    <strong class="text-success">

                        Rp {{ number_format($data->nominal_pengajuan,0,',','.') }}

                    </strong>

                </td>

            </tr>

            <tr>

                <th>

                    Tanggal Pengajuan

                </th>

                <td>

                    {{ $data->tanggal_pengajuan
                        ? \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y')
                        : '-' }}

                </td>

            </tr>

            <tr>

                <th>

                    Tanggal Persetujuan

                </th>

                <td>

                    {{ $data->tanggal_persetujuan
                        ? \Carbon\Carbon::parse($data->tanggal_persetujuan)->translatedFormat('d F Y')
                        : '-' }}

                </td>

            </tr>

            <tr>

                <th>

                    Tanggal Pencairan

                </th>

                <td>

                    {{ $data->tanggal_pencairan
                        ? \Carbon\Carbon::parse($data->tanggal_pencairan)->translatedFormat('d F Y')
                        : '-' }}

                </td>

            </tr>

            <tr>

                <th>

                    Keterangan

                </th>

                <td>

                    {{ $data->keterangan ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>

                    Bukti Transfer

                </th>

                <td>

                    @if($data->bukti)

                        <a
                            href="{{ asset('storage/'.$data->bukti) }}"
                            target="_blank"
                            class="btn btn-info btn-sm">

                            <i class="fas fa-file-image"></i>

                            Lihat Bukti Transfer

                        </a>

                    @else

                        <span class="text-muted">

                            Belum tersedia

                        </span>

                    @endif

                </td>

            </tr>

        </table>

    </div>

</div>



<div class="card card-outline card-info">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-stream"></i>

            Riwayat Proses Pencairan

        </h3>

    </div>

    <div class="card-body">

        <ul class="timeline">

            <li>

                <i class="fas fa-paper-plane bg-primary"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ $data->tanggal_pengajuan
                            ? \Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y')
                            : '-' }}

                    </span>

                    <h3 class="timeline-header">

                        Pengajuan dibuat oleh anggota

                    </h3>

                </div>

            </li>

            @if($data->tanggal_persetujuan)

            <li>

                <i class="fas fa-check bg-success"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ \Carbon\Carbon::parse($data->tanggal_persetujuan)->translatedFormat('d F Y') }}

                    </span>

                    <h3 class="timeline-header">

                        Pengajuan disetujui

                    </h3>

                </div>

            </li>

            @endif

            @if($data->tanggal_pencairan)

            <li>

                <i class="fas fa-wallet bg-info"></i>

                <div class="timeline-item">

                    <span class="time">

                        <i class="far fa-calendar"></i>

                        {{ \Carbon\Carbon::parse($data->tanggal_pencairan)->translatedFormat('d F Y') }}

                    </span>

                    <h3 class="timeline-header">

                        Dana SHU berhasil dicairkan

                    </h3>

                </div>

            </li>

            @endif

        </ul>

    </div>

</div>

<hr>
{{-- ==========================================
        AKSI BERDASARKAN ROLE
========================================== --}}

<div class="card">

    <div class="card-footer">

        {{-- =====================================
                ANGGOTA
        ====================================== --}}

        @role('anggota')

            @if($data->status == 'menunggu')

                <a
                    href="{{ route('pencairan.show',$data->id) }}"
                    class="btn btn-warning">

                    <i class="fas fa-edit"></i>

                    Ubah Pengajuan

                </a>

            @elseif($data->status == 'disetujui')

                <button
                    class="btn btn-primary"
                    disabled>

                    <i class="fas fa-clock"></i>

                    Menunggu Proses Pencairan

                </button>

            @elseif($data->status == 'dicairkan')

                <button
                    class="btn btn-success"
                    disabled>

                    <i class="fas fa-check-circle"></i>

                    SHU Sudah Dicairkan

                </button>

            @elseif($data->status == 'ditolak')

                <button
                    class="btn btn-danger"
                    disabled>

                    <i class="fas fa-times-circle"></i>

                    Pengajuan Ditolak

                </button>

            @endif

        @endrole


        {{-- =====================================
                ADMIN / BENDAHARA
        ====================================== --}}

        @role('admin|bendahara')

            @if($data->status == 'menunggu')

                <form
                    action="{{ route('pencairan.approve',$data->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('PUT')

                    <button
                        class="btn btn-success">

                        <i class="fas fa-check"></i>

                        Setujui

                    </button>

                </form>

                <button
                    class="btn btn-danger"
                    data-toggle="modal"
                    data-target="#modalReject">

                    <i class="fas fa-times"></i>

                    Tolak

                </button>

            @elseif($data->status == 'disetujui')

                <button
                    class="btn btn-primary"
                    data-toggle="modal"
                    data-target="#modalCairkan">

                    <i class="fas fa-upload"></i>

                    Upload Bukti Transfer

                </button>

            @elseif($data->status == 'dicairkan')

                @if($data->bukti)

                    <a
                        href="{{ asset('storage/'.$data->bukti) }}"
                        target="_blank"
                        class="btn btn-success">

                        <i class="fas fa-file-image"></i>

                        Lihat Bukti Transfer

                    </a>

                @endif

            @endif

        @endrole

    </div>

</div>



{{-- ==========================================
        MODAL REJECT
========================================== --}}

<div
    class="modal fade"
    id="modalReject">

    <div class="modal-dialog">

        <form
            action="{{ route('pencairan.reject',$data->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-danger">

                    <h5 class="modal-title text-white">

                        Tolak Pengajuan

                    </h5>

                    <button
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
                            rows="5"
                            class="form-control"
                            required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
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
        MODAL UPLOAD BUKTI
========================================== --}}

<div
    class="modal fade"
    id="modalCairkan">

    <div class="modal-dialog">

        <form
            action="{{ route('pencairan.cairkan',$data->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header bg-primary">

                    <h5 class="modal-title text-white">

                        Upload Bukti Transfer

                    </h5>

                    <button
                        class="close text-white"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">

                        Setelah bukti transfer diunggah, status pencairan akan berubah menjadi
                        <strong>Dicairkan</strong>.

                    </div>

                    <div class="form-group">

                        <label>

                            Bukti Transfer

                        </label>

                        <input
                            type="file"
                            name="bukti"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-primary">

                        <i class="fas fa-save"></i>

                        Simpan & Cairkan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
{{-- ==========================================
        STYLE
========================================== --}}

<style>

.card{
    border-radius:12px;
}

.card-header{
    font-weight:600;
}

.table th{
    width:30%;
    background:#f8f9fa;
}

.table td,
.table th{
    vertical-align:middle !important;
}

.badge{
    font-size:13px;
    padding:8px 12px;
}

.timeline{
    list-style:none;
    padding-left:0;
}

.timeline li{
    position:relative;
    padding-left:45px;
    margin-bottom:25px;
}

.timeline li i{

    position:absolute;
    left:0;
    top:0;

    width:30px;
    height:30px;

    line-height:30px;

    text-align:center;

    border-radius:50%;

    color:#fff;

}

.preview-image{

    display:none;

    width:100%;

    max-height:350px;

    object-fit:contain;

    margin-top:15px;

    border-radius:8px;

    border:1px solid #ddd;

}

</style>



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

    setTimeout(function(){

        $('.alert').fadeOut('slow');

    },4000);



    /*
    |--------------------------------------------------------------------------
    | Reset Form Modal
    |--------------------------------------------------------------------------
    */

    $('.modal').on('hidden.bs.modal',function(){

        let form=$(this).find('form');

        if(form.length){

            form[0].reset();

        }

        $(this)
            .find('.preview-image')
            .attr('src','')
            .hide();

    });



    /*
    |--------------------------------------------------------------------------
    | Preview Bukti Transfer
    |--------------------------------------------------------------------------
    */

    $('input[name=bukti]').change(function(){

        let input=this;

        if(input.files && input.files[0]){

            let file=input.files[0];

            if(file.type.startsWith('image/')){

                let reader=new FileReader();

                reader.onload=function(e){

                    $('.preview-image')
                        .attr('src',e.target.result)
                        .show();

                }

                reader.readAsDataURL(file);

            }

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Approve
    |--------------------------------------------------------------------------
    */

    $('form[action*="approve"]').submit(function(){

        return confirm(

            'Setujui pengajuan pencairan SHU ini?'

        );

    });



    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Reject
    |--------------------------------------------------------------------------
    */

    $('form[action*="reject"]').submit(function(){

        return confirm(

            'Yakin ingin menolak pengajuan ini?'

        );

    });



    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Cairkan
    |--------------------------------------------------------------------------
    */

    $('form[action*="cairkan"]').submit(function(){

        return confirm(

            'Pastikan dana sudah ditransfer dan bukti transfer sudah benar. Lanjutkan pencairan SHU?'

        );

    });

});

</script>

@endpush

@endsection