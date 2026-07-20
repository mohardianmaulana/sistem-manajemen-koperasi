<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Tagihan Angsuran</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2,
        .header h3,
        .header p {
            margin: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
        }

        th {
            background: #d9d9d9;
            text-align: center;
        }

        td {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>KOPERASI KARYAWAN POLITEKNIK NEGERI BANYUWANGI</h2>
        <h3>DATA TAGIHAN ANGSURAN AUTO DEBET</h3>
        <p>
            Periode
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="22%">Nama Anggota</th>
                <th width="10%">Angsuran</th>
                <th width="18%">Jatuh Tempo</th>
                <th width="20%">Nominal</th>
            </tr>
        </thead>

        <tbody>

            @forelse($tagihan as $item)
                <tr>
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $item->pinjaman->pengajuan->users->name }}
                    </td>

                    <td class="text-center">
                        Ke-{{ $item->angsuran_ke }}
                    </td>

                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->locale('id')->translatedFormat('d F Y') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($item->jumlah_angsuran,0,',','.') }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        Tidak terdapat tagihan angsuran pada bulan ini.
                    </td>
                </tr>

            @endforelse

        </tbody>

        @if($tagihan->count() > 0)
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">
                    Total Tagihan
                </th>

                <th class="text-right">
                    Rp {{ number_format($tagihan->sum('jumlah_angsuran'),0,',','.') }}
                </th>

            </tr>
        </tfoot>
        @endif

    </table>

    <div class="footer">
        Dicetak pada :
        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y H:i') }}
    </div>

</body>

</html>