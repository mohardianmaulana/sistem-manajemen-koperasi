@extends('adminlte::page')

@section('title', 'Detail Pencairan Simpanan')

@section('content')

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>Detail Pencairan Simpanan</h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">
                        <a href="{{ route('pencairan-simpanan.index') }}">
                            Pencairan Simpanan
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Detail
                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>

<section class="content">

<div class="container-fluid">

<div class="row">

<div class="col-md-6">

<div class="card">

<div class="card-header">

<h3 class="card-title">

Informasi Pengajuan

</h3>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>
    <th width="40%">Kode</th>
    <td>{{ $data->kode_pencairan }}</td>
</tr>

<tr>
    <th>Nama Anggota</th>
    <td>{{ $data->anggota->name }}</td>
</tr>

<tr>
    <th>Jumlah Pencairan Simpanan Sukarela</th>
    <td>
        Rp {{ number_format($data->nominal_pencairan,0,',','.') }}
    </td>
</tr>

<tr>
    <th>Status</th>

    <td>

        {{ ucfirst($data->status) }}

    </td>

</tr>

<tr>

    <th>Tanggal Pengajuan</th>

    <td>

        {{ $data->created_at->format('d F Y') }}

    </td>

</tr>


</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card">

<div class="card-header">

<h3 class="card-title">

Informasi Verifikasi

</h3>

</div>

<div class="card-body">

<table class="table table-borderless">

<tr>

<th width="40%">

Koordinator

</th>

<td>

{{ optional($data->verifikator)->name ?? '-' }}

</td>

</tr>

<tr>

<th>

Tanggal Verifikasi

</th>

<td>

{{ optional($data->tanggal_verifikasi)->format('d F Y') ?? '-' }}

</td>

</tr>

<tr>

<th>

Bendahara

</th>

<td>

{{ optional($data->bendahara)->name ?? '-' }}

</td>

</tr>

<tr>

<th>

Tanggal Pencairan

</th>

<td>

{{ optional($data->tanggal_pencairan)->format('d F Y') ?? '-' }}

</td>

</tr>

<tr>

<th>

Catatan

</th>

<td>

{{ $data->catatan ?? '-' }}

</td>

</tr>


</table>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-footer text-right">

<a
href="{{ route('pencairan-simpanan.index') }}"
class="btn btn-secondary">

Kembali

</a>

</div>

</div>

</div>

</section>

@endsection