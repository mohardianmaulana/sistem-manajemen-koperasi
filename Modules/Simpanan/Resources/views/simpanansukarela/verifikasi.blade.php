@extends('adminlte::page')

@section('title','Verifikasi Simpanan Sukarela')

@section('content_header')
<h1>Verifikasi Simpanan Sukarela</h1>
@stop

@section('content')

<div class="card">

    <div class="card-body">

        <form
            action="{{ route('simpanan-sukarela.update-status',$simpanan->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nama Anggota</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $simpanan->user->name }}"
                    readonly>

            </div>

            <div class="form-group">

                <label>Nominal</label>

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

            @if($simpanan->bukti)

                <div class="mb-3">

                    <a
                        href="{{ asset('storage/'.$simpanan->bukti) }}"
                        target="_blank"
                        class="btn btn-info">

                        Lihat Bukti Transfer

                    </a>

                </div>

            @endif

            <div class="form-group">

                <label>Status</label>

                <select
                    name="status"
                    class="form-control @error('status') is-invalid @enderror">

                    <option value="">-- Pilih Status --</option>

                    <option value="selesai">

                        Selesai

                    </option>

                    <option value="tidak berhasil">

                        Tidak Berhasil

                    </option>

                </select>

                @error('status')

                    <span class="invalid-feedback d-block">

                        {{ $message }}

                    </span>

                @enderror

            </div>

            <button class="btn btn-success">

                Simpan Verifikasi

            </button>

        </form>

    </div>

</div>

@stop