@extends('adminlte::page')

@section('title', 'Daftar pengajuan pinjaman')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar pengajuan pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    {{-- TABEL --}}
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Skema pinjaman</th>
                                    <th class="text-center">Jumlah pengajuan</th>
                                    <th class="text-center">Lama angsuran</th>
                                    <th class="text-center">Tanggal pengajuan</th>
                                    <th class="text-center">Detail</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuanPinjaman as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->users->name }}</td>
                                        <td>
                                            {{ $item->skemaPinjaman->nama }}
                                        </td>
                                        <td>Rp. {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}</td>
                                        <td>
                                            {{ $item->lama_angsuran }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                            </button>
                                            @if($item->status_pengajuan == 'persetujuan_akhir')
                                            <a href="{{ route('pengajuanPinjaman.cetak', ['id' => $item->id]) }}" class="btn btn-danger btn-sm">
                                                <i class="fas fa-file-pdf"></i>
                                                Dokumen
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status_pengajuan == 'menunggu')
                                            <form action="{{ route('pengajuanPinjaman.updateStatus', $item->id) }}"
                                                method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    Verifikasi
                                                </button>
                                            </form>
                                            @elseif($item->status_pengajuan == 'verifikasi')
                                            @php
                                                $semuaTerverifikasi = $item->jaminan->every(function ($jaminan) {
                                                    return $jaminan->pivot->status_verifikasi === 'verifikasi';
                                                });
                                            @endphp
                                            <form action="{{ route('pengajuanPinjaman.teruskan', $item->id) }}"
                                                method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                        class="btn btn-primary btn-sm"
                                                        {{ !$semuaTerverifikasi ? 'disabled' : '' }}>
                                                    Teruskan
                                                </button>
                                            </form>
                                            @elseif($item->status_pengajuan == 'persetujuan_akhir')
                                            <button type="button"
                                                    class="btn btn-success btn-sm btn-pencairan"
                                                    data-toggle="modal"
                                                    data-target="#modalPencairan"
                                                    data-id="{{ $item->id }}">
                                                Pencairan
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Data pinjaman belum tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

<div class="modal fade"
        id="modalPencairan"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">

    <div class="modal-dialog" role="document">
        <form id="formPencairan"
                method="POST"
                enctype="multipart/form-data">

            @csrf
            @method('PATCH')

            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        Konfirmasi Pencairan Pinjaman
                    </h5>

                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Upload Dokumen yang Sudah Ditandatangani
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                                name="dokumen_ttd"
                                class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required>

                        <small class="text-muted">
                            Format yang diperbolehkan:
                            PDF, JPG, JPEG, PNG.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        Simpan & Pencairan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach ($pengajuanPinjaman as $item)
<div class="modal fade"
    id="detailModal{{ $item->id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-primary">

                <h5 class="modal-title">
                    Detail pengajuan pinjaman
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            {{-- BODY --}}
            <div class="modal-body">

                {{-- =========================
                    DATA PEMOHON
                ========================== --}}
                <h5 class="mt-4 mb-3">
                    <i class="fas fa-user"></i>
                    Data Pemohon
                </h5>

                <table class="table table-bordered">
                    <tr>
                        <th width="25%">Nama</th>
                        <td>{{ $item->users->name }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $item->no_hp }}</td>
                    </tr>
                    <tr>
                        <th>No KTP</th>
                        <td>{{ $item->no_ktp }}</td>
                    </tr>
                    <tr>
                        <th>No Rekening</th>
                        <td>{{ $item->no_rekening }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $item->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Nama istri/suami</th>
                        <td>{{ $item->nama_istri_suami }}</td>
                    </tr>
                </table>

                {{-- =========================
                    DATA PENGAJUAN
                ========================== --}}
                <h5 class="mt-4 mb-3">
                    <i class="fas fa-file-alt"></i>
                    Data Pengajuan
                </h5>

                <table class="table table-bordered">
                    <tr>
                        <th width="25%">Skema</th>
                        <td>{{ $item->skemaPinjaman->nama }}</td>
                    </tr>
                    <tr>
                        <th>Pengajuan</th>
                        <td>
                            Rp.
                            {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Tenor</th>
                        <td>
                            {{ $item->lama_angsuran }} bulan
                        </td>
                    </tr>
                    <tr>
                        <th>Bunga</th>
                        <td>
                            {{ $item->skemaPinjaman->bunga }} %
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal pengajuan</th>
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                </table>

                {{-- =========================
                    DOKUMEN PINJAMAN
                ========================== --}}
                <h5 class="mt-4 mb-3">
                    <i class="fas fa-file-alt"></i>
                    Dokumen Pinjaman
                </h5>

                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Dokumen</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Form Pengajuan Pinjaman</td>
                            <td class="text-center">
                                <a href="{{ route('pengajuanPinjaman.cetak', ['id' => $item->id]) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                @if($item->status_pengajuan == 'verifikasi')
                {{-- =========================
                    DOKUMEN JAMINAN
                ========================== --}}
                <h5 class="mt-4 mb-3">
                    <i class="fas fa-folder-open"></i>
                    Dokumen Jaminan
                </h5>

                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Jaminan</th>
                            <th width="20%">File</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($item->jaminan as $jaminan)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $jaminan->nama }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset('jaminan/'.$jaminan->pivot->file_jaminan) }}"
                                        target="_blank"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($jaminan->pivot->status_verifikasi == 'menunggu')
                                        <span class="badge badge-warning">
                                            Menunggu
                                        </span>
                                    @elseif ($jaminan->pivot->status_verifikasi == 'verifikasi')
                                        <span class="badge badge-success">
                                            Verifikasi
                                        </span>
                                    @elseif ($jaminan->pivot->status_verifikasi == 'ditolak')
                                        <span class="badge badge-danger">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($jaminan->pivot->status_verifikasi == 'menunggu')
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-gagal"
                                                data-toggle="modal"
                                                data-target="#modalTolak"
                                                data-id="{{ $item->id }}"
                                                data-jaminan="{{ $jaminan->pivot->id_jaminan }}">
                                                <i class="fa-solid fa-xmark"></i>
                                        </button>
                                        <form action="{{ route('pengajuanPinjaman.verifikasi', ['id' => $item->id]) }}"
                                            method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                    name="id_jaminan"
                                                    value="{{ $jaminan->pivot->id_jaminan }}">

                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @elseif($jaminan->pivot->status_verifikasi == 'ditolak')
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-gagal"
                                                data-toggle="modal"
                                                data-target="#modalDitolak"
                                                data-id="{{ $jaminan->pivot->id }}"
                                                data-keterangan="{{ $jaminan->pivot->keterangan }}">
                                                <i class="fa-solid fa-eye"></i>
                                                Lihat keterangan
                                        </button>
                                    @elseif($jaminan->pivot->status_verifikasi == 'verifikasi')
                                        <button class="btn btn-success btn-sm" disabled>
                                            Disetujui
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada dokumen jaminan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade"
        id="modalTolak"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">

    <div class="modal-dialog" role="document">

        <form method="POST" id="formTolak">
            @csrf
            @method('PATCH')

            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">
                        Tolak Verifikasi Jaminan
                    </h5>

                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden"
                            name="id_jaminan"
                            id="idJaminan">

                    <div class="form-group">
                        <label>
                            Alasan Penolakan
                        </label>

                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alasan penolakan..."
                            required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Tolak Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade"
        id="modalDitolak"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    Keterangan Penolakan
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>
                        Catatan Koordinator
                    </label>

                    <textarea
                        class="form-control"
                        id="lihatKeterangan"
                        rows="5"
                        readonly></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@section('css')
	 <!--some css
    <link rel="stylesheet" href="/assets/css/admin_custom.css">-->
@stop
@push('js')
<script>
$('#modalTolak').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);

    let id = button.data('id');
    let idJaminan = button.data('jaminan');

    let url = "{{ route('pengajuanPinjaman.tolak', ':id') }}";
    url = url.replace(':id', id);

    $('#formTolak').attr('action', url);

    $('#idJaminan').val(idJaminan);
});

$('#modalDitolak').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);

    let keterangan = button.data('keterangan');

    $('#lihatKeterangan').val(keterangan);
});

$('#modalPencairan').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);

    let id = button.data('id');

    let url = "{{ route('persetujuan.persetujuanAkhir', ':id') }}";
    url = url.replace(':id', id);

    $('#formPencairan').attr('action', url);
});

</script>
@endpush