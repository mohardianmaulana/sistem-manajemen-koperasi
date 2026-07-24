<div class="card card-outline card-primary">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-chart-line mr-2"></i>

            Grafik Perkembangan SHU

        </h3>

    </div>

    <div class="card-body">

        <canvas id="grafikShu" height="100"></canvas>

    </div>

</div>

@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const labels = [

@foreach($grafik as $item)

'{{ \Carbon\Carbon::parse($item->periode_akhir)->format('Y') }}',

@endforeach

];

const data = [

@foreach($grafik as $item)

{{ $item->shu_anggota }},

@endforeach

];

const ctx = document.getElementById('grafikShu');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [{

            label: 'Total SHU',

            data: data,

            borderColor: '#28a745',

            backgroundColor: 'rgba(40,167,69,0.15)',

            borderWidth: 3,

            fill: true,

            tension: 0.4,

            pointRadius: 5,

            pointHoverRadius: 7

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                display: true

            }

        },

        scales: {

            y: {

                beginAtZero: true,

                ticks: {

                    callback: function(value) {

                        return 'Rp ' + value.toLocaleString('id-ID');

                    }

                }

            }

        }

    }

});

</script>

@endpush