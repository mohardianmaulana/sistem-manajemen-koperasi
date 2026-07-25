<div class="row">

    <div class="col-md-12">

        <div class="card card-success card-outline shadow-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h3 class="font-weight-bold mb-1">
                            SHU Periode Terbaru
                        </h3>

                        @if($summary)

                            <p class="text-muted mb-3">

                                Periode

                                <strong>
                                    {{ \Carbon\Carbon::parse($summary->periode_awal)->translatedFormat('d F Y') }}
                                </strong>

                                -

                                <strong>
                                    {{ \Carbon\Carbon::parse($summary->periode_akhir)->translatedFormat('d F Y') }}
                                </strong>

                            </p>

                            <h1 class="font-weight-bold text-success mb-3">

                                Rp {{ number_format($summary->sisa_shu, 0, ',', '.') }}

                            </h1>

                            {{-- Tombol Pengajuan Pencairan --}}
                            <div class="mb-3">

                                @if(
                                    !$summary->pencairan ||
                                    in_array($summary->pencairan->status, ['ditolak', 'dicairkan'])
                                )

                                    <form action="{{ route('pengajuan-pencairan.form') }}" method="GET">

                                        <input type="hidden"
                                            name="id_shu_anggota"
                                            value="{{ $summary->id }}">

                                        <button type="submit" class="btn btn-success">

                                            <i class="fas fa-hand-holding-usd"></i>

                                            Ajukan Pencairan SHU

                                        </button>

                                    </form>

                                    @if(
                                        $summary->pencairan &&
                                        $summary->pencairan->status == 'ditolak' &&
                                        $summary->pencairan->keterangan
                                    )

                                        <div class="alert alert-danger mt-3 mb-0">

                                            <strong>Pengajuan sebelumnya ditolak.</strong><br>

                                            Alasan: {{ $summary->pencairan->keterangan }}

                                        </div>

                                    @endif

                                @elseif($summary->pencairan->status == 'menunggu')

                                    <button class="btn btn-warning" disabled>

                                        <i class="fas fa-clock"></i>

                                        Menunggu Persetujuan

                                    </button>

                                @elseif($summary->pencairan->status == 'disetujui')

                                    <button class="btn btn-primary" disabled>

                                        <i class="fas fa-check-circle"></i>

                                        Pengajuan Disetujui

                                    </button>

                                @endif

                            </div>

                            <span class="badge badge-success">

                                Total SHU Anggota

                            </span>

                        @else

                            <div class="alert alert-warning mb-0">

                                Belum terdapat data SHU.

                            </div>

                        @endif

                    </div>

                    <div class="col-md-4 text-center">

                        <i class="fas fa-wallet fa-5x text-success opacity-50"></i>

                    </div>

                </div>

            </div>

            @if($summary)

                <div class="card-footer d-flex justify-content-between align-items-center">

                    <small class="text-muted">

                        Terakhir diperbarui

                        {{ $summary->updated_at->translatedFormat('d F Y H:i') }}

                    </small>

                    @if($summary->pencairan)

                        <small>

                            Status :

                            @switch($summary->pencairan->status)

                                @case('menunggu')
                                    <span class="badge badge-warning">
                                        Menunggu
                                    </span>
                                    @break

                                @case('disetujui')
                                    <span class="badge badge-primary">
                                        Disetujui
                                    </span>
                                    @break

                                @case('ditolak')
                                    <span class="badge badge-danger">
                                        Ditolak
                                    </span>
                                    @break

                                @case('dicairkan')
                                    <span class="badge badge-success">
                                        Dicairkan
                                    </span>
                                    @break

                            @endswitch

                        </small>

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>