@extends('adminlte::page')

@section('title', 'Edit Pengajuan Simpanan Sukarela')

@section('content_header')
<h1>Edit Pengajuan Simpanan Sukarela</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('simpanan-sukarela.update', $simpanan->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nominal Simpanan</label>

                <input
                    type="number"
                    name="nilai"
                    value="{{ old('nilai',$simpanan->nilai) }}"
                    class="form-control @error('nilai') is-invalid @enderror">

                @error('nilai')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label>Periode</label>

                <input
                    type="date"
                    name="periode"
                    value="{{ old('periode',$simpanan->periode) }}"
                    class="form-control @error('periode') is-invalid @enderror">

                @error('periode')
                    <span class="invalid-feedback d-block">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <button class="btn btn-primary">

                <i class="fas fa-save"></i>

                Update Pengajuan

            </button>

        </form>

    </div>
</div>

@stop