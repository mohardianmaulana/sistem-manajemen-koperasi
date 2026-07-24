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

                                Rp {{ number_format($summary->shu_anggota,0,',','.') }}

                            </h1>

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

                <div class="card-footer">

                    <small class="text-muted">

                        Terakhir diperbarui

                        {{ $summary->updated_at->translatedFormat('d F Y H:i') }}

                    </small>

                </div>

            @endif

        </div>

    </div>

</div>