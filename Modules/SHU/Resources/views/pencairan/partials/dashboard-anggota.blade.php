<div class="row">

    {{-- SHU Simpanan --}}
    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3>
                    Rp {{ number_format($summary['shu_simpanan'], 0, ',', '.') }}
                </h3>

                <p>SHU Simpanan</p>

            </div>

            <div class="icon">
                <i class="fas fa-piggy-bank"></i>
            </div>

        </div>

    </div>

    {{-- SHU Pinjaman --}}
    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h3>
                    Rp {{ number_format($summary['shu_pinjaman'], 0, ',', '.') }}
                </h3>

                <p>SHU Pinjaman</p>

            </div>

            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>

        </div>

    </div>

    {{-- Pajak --}}
    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3>
                    Rp {{ number_format($summary['pajak'], 0, ',', '.') }}
                </h3>

                <p>Pajak</p>

            </div>

            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

        </div>

    </div>

    {{-- SHU Bersih --}}
    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3>
                    Rp {{ number_format($summary['shu_bersih'], 0, ',', '.') }}
                </h3>

                <p>SHU Bersih</p>

            </div>

            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>

        </div>

    </div>

</div>


<div class="row">

    {{-- Total Dicairkan --}}
    <div class="col-lg-6">

        <div class="small-box bg-teal">

            <div class="inner">

                <h3>
                    Rp {{ number_format($summary['total_dicairkan'], 0, ',', '.') }}
                </h3>

                <p>Total SHU Dicairkan</p>

            </div>

            <div class="icon">
                <i class="fas fa-money-check-alt"></i>
            </div>

        </div>

    </div>

    {{-- Status --}}
    <div class="col-lg-6">

        @php

            $status = $summary['status_pencairan'];

            if ($status == 'Sudah Dicairkan') {

                $bg = 'bg-success';
                $icon = 'fas fa-check-circle';

            } else {

                $bg = 'bg-warning';
                $icon = 'fas fa-clock';

            }

        @endphp

        <div class="small-box {{ $bg }}">

            <div class="inner">

                <h3>

                    {{ $status }}

                </h3>

                <p>Status Pencairan SHU</p>

            </div>

            <div class="icon">
                <i class="{{ $icon }}"></i>
            </div>

        </div>

    </div>

</div>