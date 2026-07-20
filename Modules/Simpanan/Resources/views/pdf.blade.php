<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            margin:30px;
        }

        .header{
            text-align:center;
        }

        .header h2,
        .header h3,
        .header p{
            margin:2px;
        }

        .garis{
            border-top:3px solid black;
            border-bottom:1px solid black;
            margin-top:10px;
            margin-bottom:20px;
        }

        .judul{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid black;
            padding:6px;
        }

        table th{
            background:#d9d9d9;
            text-align:center;
        }

        .center{
            text-align:center;
        }

        .right{
            text-align:right;
        }

        .total{
            font-weight:bold;
            background:#eeeeee;
        }

        .ttd{
            margin-top:70px;
            width:100%;
        }

        .ttd td{
            border:none;
        }

    </style>

</head>

<body>

    <div class="header">

        <h2>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>

        <h2>POLITEKNIK NEGERI BANYUWANGI</h2>

        <p>
            Jl. Raya Jember Km.13 Labanasem, Kabat, Banyuwangi 68461
        </p>

        <p>
            Telp. (0333) 636780
        </p>

        <p>
            www.poliwangi.ac.id
        </p>

    </div>

    <div class="garis"></div>

    <div class="judul">

        <h3>DAFTAR POTONGAN GAJI KARYAWAN</h3>

        <strong>(AUTO DEBIT SIMPANAN SUKARELA)</strong>

    </div>

    <table>

        <thead>

            <tr>

                <th width="8%">No</th>

                <th width="35%">Nama</th>

                <th width="22%">No Rekening</th>

                <th width="20%">Jenis Simpanan</th>

                <th width="15%">Jumlah</th>

            </tr>

        </thead>

        <tbody>

            @foreach($data as $item)

                <tr>

                    <td class="center">

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $item->name }}

                    </td>

                    <td class="center">

                        {{ $item->no_rek }}

                    </td>

                    <td class="center">

                        Simpanan Sukarela

                    </td>

                    <td class="right">

                        {{ number_format($item->nilai,0,',','.') }}

                    </td>

                </tr>

            @endforeach

            <tr class="total">

                <td colspan="4" class="right">

                    TOTAL

                </td>

                <td class="right">

                    {{ number_format($total,0,',','.') }}

                </td>

            </tr>

        </tbody>

    </table>

    <table class="ttd">

        <tr>

            <td width="40%" class="center">

                Mengetahui,

                <br>

                Bendahara

                <br><br><br><br><br>

                (...................................)

            </td>

            <td width="20%"></td>

            <td width="40%" class="center">

                Banyuwangi,

                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

                <br>

                Petugas

                <br><br><br><br><br>

                (...................................)

            </td>

        </tr>

    </table>

</body>

</html>