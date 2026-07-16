@extends('adminlte::page')

@section('title', 'Edit SHU Koperasi')

@section('content_header')
<h1 class="m-0 text-dark">
    Edit SHU Koperasi
</h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-10">

        <div class="mb-3">

            <a href="{{ route('shu-koperasi.index') }}"
                class="btn btn-secondary"
                style="border-radius:10px">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

        <div class="card">

            <div class="card-header">

                <h4 class="mb-0">
                    Form Edit SHU Koperasi
                </h4>

            </div>

            <div class="card-body">

                <form
                    action="{{ route('shu-koperasi.update', $shu->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    {{-- PERIODE SHU --}}

                    <h5 class="mb-3">

                        Periode SHU

                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Periode Awal

                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="periode_awal"
                                    class="form-control @error('periode_awal') is-invalid @enderror"
                                    value="{{ old('periode_awal', $shu->periode_awal) }}">

                                @error('periode_awal')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Periode Akhir

                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="periode_akhir"
                                    class="form-control @error('periode_akhir') is-invalid @enderror"
                                    value="{{ old('periode_akhir', $shu->periode_akhir) }}">

                                @error('periode_akhir')

                                    <span class="invalid-feedback d-block">

                                        {{ $message }}

                                    </span>

                                @enderror

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- TOTAL SHU --}}

                    <div class="form-group">

                        <label>

                            Total SHU

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <div class="input-group-prepend">

                                <span class="input-group-text">

                                    Rp

                                </span>

                            </div>

                            <input
                                type="number"
                                id="total_shu"
                                name="total_shu"
                                min="0"
                                class="form-control @error('total_shu') is-invalid @enderror"
                                value="{{ old('total_shu', $shu->total_shu) }}"
                                placeholder="Masukkan Total SHU">

                        </div>

                        @error('total_shu')

                            <span class="invalid-feedback d-block">

                                {{ $message }}

                            </span>

                        @enderror

                    </div>

                    <hr>

                    {{-- PEMBAGIAN SHU --}}

                    <h5 class="mb-3">

                        Pembagian SHU (%)

                    </h5>

                    <div class="alert alert-info">

                        Total seluruh persentase harus bernilai
                        <strong>100%</strong>

                    </div>

                    <div class="row">

                        {{-- Jasa Simpanan --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Jasa Simpanan

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="persen_jasa_simpanan"
                                        name="persen_jasa_simpanan"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        value="{{ old('persen_jasa_simpanan', $shu->persen_jasa_simpanan) }}">

                                    <div class="input-group-append">

                                        <span class="input-group-text">

                                            %

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Jasa Pinjaman --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label>

                                    Jasa Pinjaman

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="persen_jasa_pinjaman"
                                        name="persen_jasa_pinjaman"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        value="{{ old('persen_jasa_pinjaman', $shu->persen_jasa_pinjaman) }}">

                                    <div class="input-group-append">

                                        <span class="input-group-text">

                                            %

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Dana Cadangan --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Dana Cadangan

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="persen_dana_cadangan"
                                        name="persen_dana_cadangan"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        value="{{ old('persen_dana_cadangan', $shu->persen_dana_cadangan) }}">

                                    <div class="input-group-append">

                                        <span class="input-group-text">

                                            %

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Jasa Pengurus --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Jasa Pengurus

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="persen_jasa_pengurus"
                                        name="persen_jasa_pengurus"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        value="{{ old('persen_jasa_pengurus', $shu->persen_jasa_pengurus) }}">

                                    <div class="input-group-append">

                                        <span class="input-group-text">

                                            %

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Dana Sosial --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    Dana Sosial

                                </label>

                                <div class="input-group">

                                    <input
                                        type="number"
                                        id="persen_dana_sosial"
                                        name="persen_dana_sosial"
                                        class="form-control"
                                        min="0"
                                        max="100"
                                        value="{{ old('persen_dana_sosial', $shu->persen_dana_sosial) }}">

                                    <div class="input-group-append">

                                        <span class="input-group-text">

                                            %

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">

                        Hasil Perhitungan

                    </h5>

                    <table class="table table-bordered">

    <thead class="thead-light">

        <tr>

            <th width="40%">Komponen</th>

            <th width="20%">Persentase</th>

            <th>Nominal</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>Jasa Simpanan</td>

            <td id="label_simpanan">0%</td>

            <td>

                <input
                    type="text"
                    id="jasa_simpanan"
                    class="form-control"
                    readonly>

            </td>

        </tr>

        <tr>

            <td>Jasa Pinjaman</td>

            <td id="label_pinjaman">0%</td>

            <td>

                <input
                    type="text"
                    id="jasa_pinjaman"
                    class="form-control"
                    readonly>

            </td>

        </tr>

        <tr>

            <td>Dana Cadangan</td>

            <td id="label_cadangan">0%</td>

            <td>

                <input
                    type="text"
                    id="dana_cadangan"
                    class="form-control"
                    readonly>

            </td>

        </tr>

        <tr>

            <td>Jasa Pengurus</td>

            <td id="label_pengurus">0%</td>

            <td>

                <input
                    type="text"
                    id="jasa_pengurus"
                    class="form-control"
                    readonly>

            </td>

        </tr>

        <tr>

            <td>Dana Sosial</td>

            <td id="label_sosial">0%</td>

            <td>

                <input
                    type="text"
                    id="dana_sosial"
                    class="form-control"
                    readonly>

            </td>

        </tr>

    </tbody>

</table>

<div
    id="statusPersentase"
    class="alert alert-danger">

    <strong>

        Total Persentase :

        <span id="total_persen">

            0%

        </span>

    </strong>

</div>

<div class="mt-3">

    <button
        type="submit"
        id="btnSimpan"
        class="btn btn-primary"
        style="border-radius:10px">

        <i class="fas fa-save"></i>

        Update

    </button>

</div>

</form>

</div>

</div>

</div>

</div>

@stop

@section('js')

<script>

function formatRupiah(angka)
{
    return "Rp " + Number(angka).toLocaleString('id-ID');
}

function hitungSHU()
{
    let total =
        Number(document.getElementById('total_shu').value) || 0;

    let simpanan =
        Number(document.getElementById('persen_jasa_simpanan').value) || 0;

    let pinjaman =
        Number(document.getElementById('persen_jasa_pinjaman').value) || 0;

    let cadangan =
        Number(document.getElementById('persen_dana_cadangan').value) || 0;

    let pengurus =
        Number(document.getElementById('persen_jasa_pengurus').value) || 0;

    let sosial =
        Number(document.getElementById('persen_dana_sosial').value) || 0;

    document.getElementById("label_simpanan").innerHTML =
        simpanan + "%";

    document.getElementById("label_pinjaman").innerHTML =
        pinjaman + "%";

    document.getElementById("label_cadangan").innerHTML =
        cadangan + "%";

    document.getElementById("label_pengurus").innerHTML =
        pengurus + "%";

    document.getElementById("label_sosial").innerHTML =
        sosial + "%";

    document.getElementById("jasa_simpanan").value =
        formatRupiah(total * simpanan / 100);

    document.getElementById("jasa_pinjaman").value =
        formatRupiah(total * pinjaman / 100);

    document.getElementById("dana_cadangan").value =
        formatRupiah(total * cadangan / 100);

    document.getElementById("jasa_pengurus").value =
        formatRupiah(total * pengurus / 100);

    document.getElementById("dana_sosial").value =
        formatRupiah(total * sosial / 100);

    let totalPersen =
        simpanan +
        pinjaman +
        cadangan +
        pengurus +
        sosial;

    document.getElementById("total_persen").innerHTML =
        totalPersen + "%";

    if(totalPersen == 100)
    {
        document
            .getElementById("statusPersentase")
            .className = "alert alert-success";

        document
            .getElementById("btnSimpan")
            .disabled = false;
    }
}

document
.querySelectorAll("input")
.forEach(function(item){

    item.addEventListener("keyup", hitungSHU);

    item.addEventListener("change", hitungSHU);

});

hitungSHU();

</script>

@stop