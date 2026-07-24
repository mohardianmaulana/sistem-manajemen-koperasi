@extends('adminlte::page')

@section('title', 'SHU Saya')

@section('content_header')

<div class="d-flex justify-content-between align-items-center">

    <div>

        <h1 class="m-0 text-dark">
            SHU Saya
        </h1>

        <small class="text-muted">
            Informasi perolehan SHU anggota koperasi
        </small>

    </div>

</div>

@stop

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('error') }}
</div>
@endif

@include('shu::shuanggota.partials.summary')

@include('shu::shuanggota.partials.statistic')

@include('shu::shuanggota.partials.grafik')

@include('shu::shuanggota.partials.riwayat')

@stop