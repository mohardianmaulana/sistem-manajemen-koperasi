@extends('adminlte::page')

@section('title', 'Ubah Pengajuan Pencairan SHU')

@section('content_header')

<h1>

    <i class="fas fa-edit text-warning"></i>

    Ubah Pengajuan Pencairan SHU

</h1>

@stop

@section('content')

<div class="container-fluid">

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="alert alert-danger">

    {{ session('error') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger">

    <ul class="mb-0">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif
<div class="card card-outline card-success">

    <div class="card-header">

        <h3 class="card-title">

            Informasi SHU

        </h3>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="30%">

                    Total SHU

                </th>

                <td>

                    Rp {{ number_format($summary['total_shu'],0,',','.') }}

                </td>

            </tr>

            <tr>

                <th>

                    Sudah Dicairkan

                </th>

                <td>

                    Rp {{ number_format($summary['total_dicairkan'],0,',','.') }}

                </td>

            </tr>

            <tr>

                <th>

                    Sedang Diproses

                </th>

                <td>

                    Rp {{ number_format($summary['total_diproses'],0,',','.') }}

                </td>

            </tr>

            <tr>

                <th>

                    Sisa SHU

                </th>

                <td>

                    <strong class="text-success">

                        Rp {{ number_format($summary['sisa_shu'],0,',','.') }}

                    </strong>

                </td>

            </tr>

        </table>

    </div>

</div>
<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Form Edit Pengajuan

        </h3>

    </div>

    <form
        action="{{ route('pengajuan-pencairan.update',$data->id) }}"
        method="POST">

        @csrf

        @method('PUT')

        <div class="card-body">

            <div class="form-group">

                <label>

                    Nominal Pengajuan

                </label>

                <input
                    type="number"
                    name="nominal_pengajuan"
                    class="form-control @error('nominal_pengajuan') is-invalid @enderror"
                    value="{{ old('nominal_pengajuan',$data->nominal_pengajuan) }}"
                    min="1"
                    max="{{ $summary['sisa_shu'] + $data->nominal_pengajuan }}"
                    required>

                @error('nominal_pengajuan')

                <span class="invalid-feedback">

                    {{ $message }}

                </span>

                @enderror

                <small class="text-muted">

                    Maksimal pengajuan sebesar

                    <strong>

                        Rp {{ number_format($summary['sisa_shu'] + $data->nominal_pengajuan,0,',','.') }}

                    </strong>

                </small>

            </div>

        </div>

        <div class="card-footer">

            <button
                class="btn btn-warning">

                <i class="fas fa-save"></i>

                Simpan Perubahan

            </button>

            <a
                href="{{ route('pencairan.index') }}"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

        </div>

    </form>

</div>

</div>

@endsection