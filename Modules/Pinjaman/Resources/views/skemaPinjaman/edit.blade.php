@extends('adminlte::page')

@section('title', 'Skema Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Skema Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <a href="{{ route('skemaPinjaman.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                    <i class="fa-solid fa-backward me-2"></i> Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 style="color: black;">Daftar skema pinjaman</h4>
                    {{-- FORM --}}
                    <form action="{{ route('skemaPinjaman.update', ['id' => $skemaPinjaman->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            {{-- Nama Skema --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Skema <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama skema pinjaman" value="{{ old('nama', $skemaPinjaman->nama) }}">
                                    @error('nama')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Bunga --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bunga (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="bunga" class="form-control @error('bunga') is-invalid @enderror" placeholder="Masukkan bunga" value="{{ old('bunga', $skemaPinjaman->bunga) }}">
                                    @error('bunga')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Min Nominal --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Min Nominal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Rp
                                            </span>
                                        </div>
                                        <input type="number" name="min_nominal" class="form-control @error('min_nominal') is-invalid @enderror" placeholder="Masukkan min nominal pinjaman" value="{{ old('min_nominal', $skemaPinjaman->min_nominal) }}">
                                    </div>
                                    @error('min_nominal')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Max Nominal --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Nominal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                Rp
                                            </span>
                                        </div>
                                        <input type="number" name="max_nominal" class="form-control @error('max_nominal') is-invalid @enderror" placeholder="Masukkan max nominal pinjaman" value="{{ old('max_nominal', $skemaPinjaman->max_nominal) }}">                                        
                                    </div>
                                    @error('max_nominal')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Min Tenor --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Min Tenor (Bulan) <span class="text-danger">*</span></label>
                                    <input type="number" name="min_tenor" class="form-control @error('min_tenor') is-invalid @enderror" placeholder="Masukkan min tenor" value="{{ old('min_tenor', $skemaPinjaman->min_tenor) }}">
                                    @error('min_tenor')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Max Tenor --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Tenor (Bulan) <span class="text-danger">*</span></label>
                                    <input type="number" name="max_tenor" class="form-control @error('max_tenor') is-invalid @enderror" placeholder="Masukkan max tenor" value="{{ old('max_tenor', $skemaPinjaman->max_tenor) }}">
                                    @error('max_tenor')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Deskripsi <span class="text-danger">*</span></label>
                                    <input type="text" step="0.01" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Masukkan deskripsi" value="{{ old('deskripsi', $skemaPinjaman->deskripsi) }}">
                                    @error('deskripsi')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jaminan --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Jaminan <span class="text-danger">*</span></label>
                                    <select id="is_jaminan" name="jaminan" class="form-control @error('jaminan') is-invalid @enderror">
                                    <option class="text-center" value="">-- Pilih Jaminan --</option>
                                    <option class="text-center" value="ada" {{ old('jaminan', $skemaPinjaman->jaminan) == 'ada' ? 'selected' : '' }}>Ada</option>
                                    <option class="text-center" value="tidak" {{ old('jaminan', $skemaPinjaman->jaminan) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                    @error('jaminan')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option class="text-center" value="">-- Pilih Status --</option>
                                    <option class="text-center" value="aktif"{{ old('status', $skemaPinjaman->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option class="text-center" value="nonaktif"{{ old('status', $skemaPinjaman->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                    @error('status')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $selectedJaminan = old(
                                    'jaminan_ids',
                                    $skemaPinjaman->daftarJaminan->pluck('id')->toArray()
                                );
                            @endphp

                            {{-- Jaminan --}}
                            <div class="col-md-6" id="jaminan_container" style="display: none;">
                                <div class="form-group">
                                    <label>Pilih Jaminan <span class="text-danger">*</span></label>

                                    <select name="jaminan_ids[]" multiple
                                        class="form-control @error('jaminan_ids') is-invalid @enderror">

                                        @foreach($jaminan as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $selectedJaminan) ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <small class="text-muted">
                                        Tekan CTRL + Klik untuk memilih lebih dari satu jaminan
                                    </small>

                                    @error('jaminan_ids')
                                        <span class="invalid-feedback d-block">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')
<script>
$(document).ready(function() {

    function toggleJaminan() {
        if ($('#is_jaminan').val() == 'ada') {
            $('#jaminan_container').show();
        } else {
            $('#jaminan_container').hide();
        }
    }

    toggleJaminan();

    $('#is_jaminan').change(function() {
        toggleJaminan();
    });

});
</script>
@endpush