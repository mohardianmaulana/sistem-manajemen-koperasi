@extends('adminlte::page')

@section('title', 'Hasil Simulasi Pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">
        Kalkulasi Simulasi Pinjaman
    </h1>
@stop

@section('content')

<div class="mb-2">
    <a href="{{ route('simulasiPinjaman.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
        <i class="fa-solid fa-backward me-2"></i> Kembali
    </a>
</div>

<div class="row">

    {{-- FORM INPUT --}}
    <div class="col-lg-5">

        <div class="card shadow border-0" style="border-radius: 20px;">

            <div class="card-header text-white"
                style="background: linear-gradient(135deg, #159fea, #5cb3ed); border-radius: 20px 20px 0 0;">

                <h4 class="mb-0">
                    <i class="fas fa-edit"></i>
                    Input Simulasi
                </h4>

            </div>

            <div class="card-body">

                {{-- Skema --}}
                <div class="mb-3">
                    <label><b>Skema Pinjaman</b> <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" value="{{ $skema->nama }}" readonly>
                    <input type="hidden" id="skema_id" value="{{ $skema->id }}">
                </div>

                {{-- Nominal --}}
                <div class="mb-3">
                    <label><b>Nominal Pinjaman</b> <span class="text-danger">*</span></label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>

                        <input type="number" class="form-control" id="nominal" placeholder="Masukkan nominal">
                    </div>

                    <small class="text-muted">
                        Minimal Rp {{ number_format($skema->min_nominal,0,',','.') }}
                        -
                        Maksimal Rp {{ number_format($skema->max_nominal,0,',','.') }}
                    </small>
                </div>

                {{-- Tenor --}}
                <div class="mb-3">
                    <label><b>Tenor</b> <span class="text-danger">*</span></label>
                    <select class="form-control" id="tenor">
                        <option value="">-- Pilih Tenor --</option>
                        @for($i = $skema->min_tenor; $i <= $skema->max_tenor; $i++)
                            <option value="{{ $i }}">
                                {{ $i }} Bulan
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Bunga --}}
                <div class="mb-3">
                    <label><b>Bunga</b> <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" value="{{ $skema->bunga }} %" readonly>
                </div>

                {{-- Jaminan --}}
                <div class="mb-3">
                    <label><b>Jaminan</b> <span class="text-danger">*</span></label>

                    <input type="text" class="form-control" value="{{ $skema->jaminan }}" readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- HASIL SIMULASI --}}
    <div class="col-lg-7">
        <div class="card shadow border-0" style="border-radius: 20px;">
            <div class="card-header text-white"
                style="background: linear-gradient(135deg, #343a40, #495057); border-radius: 20px 20px 0 0;">

                <h4 class="mb-0">
                    <i class="fas fa-chart-line"></i>
                    Hasil Simulasi
                </h4>
            </div>

            <div class="card-body">

                {{-- Ringkasan --}}
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="p-3 shadow-sm border rounded">
                            <small class="text-muted">
                                Total Pinjaman
                            </small>

                            <h4 class="font-weight-bold text-primary" id="totalPinjaman">
                                Rp 0
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="p-3 shadow-sm border rounded">
                            <small class="text-muted">
                                Total Bunga
                            </small>

                            <h4 class="font-weight-bold text-danger" id="totalBunga">
                                Rp 0
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="p-3 shadow-sm border rounded">
                            <small class="text-muted">
                                Total Pembayaran
                            </small>

                            <h4 class="font-weight-bold text-success" id="totalPembayaran">
                                Rp 0
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="p-3 shadow-sm border rounded">
                            <small class="text-muted">
                                Angsuran / Bulan
                            </small>

                            <h4 class="font-weight-bold text-dark" id="angsuranBulanan">
                                Rp 0
                            </h4>
                        </div>
                    </div>
                </div>

                {{-- Tabel Angsuran --}}
                <hr>

                <h5 class="mb-3">
                    Detail Angsuran
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Bulan</th>
                                <th>Pokok</th>
                                <th>Bunga</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody id="tableAngsuran">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@push('js')
<script>

function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
}

function hitungSimulasi() {

    let nominal = $('#nominal').val();
    let tenor = $('#tenor').val();
    let skema_id = $('#skema_id').val();

    if (!nominal || !tenor) {
        return;
    }

    $.ajax({
        url: "{{ route('simulasi-pinjaman.hitung') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            nominal: nominal,
            tenor: tenor,
            skema_id: skema_id
        },

        success: function(response) {

            $('#totalPinjaman').text(
                formatRupiah(response.total_pinjaman)
            );

            $('#totalBunga').text(
                formatRupiah(response.total_bunga)
            );

            $('#totalPembayaran').text(
                formatRupiah(response.total_pembayaran)
            );

            $('#angsuranBulanan').text(
                formatRupiah(response.angsuran_bulanan)
            );

            let html = '';

            response.detail.forEach(function(item) {

                html += `
                    <tr>
                        <td>${item.bulan}</td>
                        <td>${formatRupiah(item.pokok)}</td>
                        <td>${formatRupiah(item.bunga)}</td>
                        <td>${formatRupiah(item.total)}</td>
                    </tr>
                `;
            });

            $('#tableAngsuran').html(html);
        }
    });
}

$('#nominal').on('input', hitungSimulasi);
$('#tenor').on('change', hitungSimulasi);

</script>
@endpush