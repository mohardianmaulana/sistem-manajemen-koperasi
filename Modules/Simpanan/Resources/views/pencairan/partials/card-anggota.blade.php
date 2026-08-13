<div class="row">

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>
                    Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}
                </h3>

                <p>
                    Saldo Simpanan Sukarela
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3>
                    Rp {{ number_format($totalPending ?? 0, 0, ',', '.') }}
                </h3>

                <p>
                    Menunggu Verifikasi
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>

        </div>

    </div>

    <div class="col-lg-4 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>
                    Rp {{ number_format($totalDicairkan ?? 0, 0, ',', '.') }}
                </h3>

                <p>
                    Total Dicairkan
                </p>

            </div>

            <div class="icon">
                <i class="fas fa-money-check-alt"></i>
            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-body text-right">

                <form
                    action="{{ route('pencairan-simpanan.store') }}"
                    method="POST"
                    class="d-inline">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                        onclick="return confirm('Apakah Anda yakin ingin mengajukan penarikan seluruh saldo simpanan sukarela?')">

                        <i class="fas fa-plus-circle mr-1"></i>

                        Ajukan Penarikan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>