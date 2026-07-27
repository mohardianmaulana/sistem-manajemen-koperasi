<div class="row">

    {{-- SHU Simpanan --}}
    <div class="col-lg-3 col-md-6 col-sm-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h5>SHU Simpanan</h5>

                <h3>
                    Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                </h3>

                <p>Total jasa simpanan yang diterima.</p>

            </div>

            <div class="icon">
                <i class="fas fa-piggy-bank"></i>
            </div>

        </div>

    </div>

    {{-- SHU Pinjaman --}}
    <div class="col-lg-3 col-md-6 col-sm-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h5>SHU Pinjaman</h5>

                <h3>
                    Rp {{ number_format($totalPinjaman, 0, ',', '.') }}
                </h3>

                <p>Total jasa pinjaman yang diterima.</p>

            </div>

            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>

        </div>

    </div>

    {{-- Pajak --}}
    <div class="col-lg-3 col-md-6 col-sm-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h5>Total Pajak</h5>

                <h3>
                    Rp {{ number_format($totalPajak, 0, ',', '.') }}
                </h3>

                <p>Akumulasi potongan pajak SHU.</p>

            </div>

            <div class="icon">
                <i class="fas fa-percent"></i>
            </div>

        </div>

    </div>

    {{-- Total SHU --}}
    <div class="col-lg-3 col-md-6 col-sm-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h5>Total SHU</h5>

                <h3>
                    Rp {{ number_format($totalShu, 0, ',', '.') }}
                </h3>

                <p>Akumulasi SHU seluruh periode.</p>

            </div>

            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>

        </div>

    </div>

</div>