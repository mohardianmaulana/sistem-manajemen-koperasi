<!DOCTYPE html>
<html>

<head>
    <style>

        body{
            font-family: "Times New Roman";
            font-size:15px;
            line-height:1.5;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .mt-3{
            margin-top:30px;
        }

        .approval{
            margin-top:80px;
        }

        .check{
            width:45px;
        }

        .signature{
            margin-top: 30px;
            margin-left: 68%;
            text-align: center;
            width: 180px;
        }

        .nama-ttd{
            display: inline-block;
            border-bottom: 1px solid black;
            font-weight: bold;
            padding-bottom: 2px;
        }

        .tempat{
            display: inline-block;
            border-bottom: 1px solid black;
            position: relative;
            top: 20px;
            left: 20px;
        }

        .checks{
            width: 120px;
            height: 120px;
        }

    </style>
</head>

<body>

<p class="text-left">
Banyuwangi,
{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
</p>

<p>
Kepada Yth.<br>
Bendahara Koperasi Politeknik Negeri Banyuwangi<br>
di - <span class="tempat">Tempat</span>
</p>

<br>

Yang bertanda tangan di bawah ini :

<table>

<br>
<tr>
<td width="25%">Nama</td>
<td width="3%">:</td>
<td>{{ $pengajuan->users->name }}</td>
</tr>

<tr>
<td>NIK/NIP/NIPPK</td>
<td>:</td>
<td>{{ $pengajuan->users->nip }}</td>
</tr>

<tr>
<td>No KTP</td>
<td>:</td>
<td>{{ $pengajuan->no_ktp }}</td>
</tr>

<tr>
<td>Jabatan</td>
<td>:</td>
<td>{{ $pengajuan->users->jabatan_fungsional }}</td>
</tr>

<tr>
<td>Alamat</td>
<td>:</td>
<td>{{ $pengajuan->alamat }}</td>
</tr>

</table>

<br>

<p align="justify">

Dengan ini bermaksud mengajukan pinjaman kepada Koperasi Karyawan
Politeknik Negeri Banyuwangi sebesar

<b>
Rp {{ number_format($pengajuan->jumlah_pengajuan,0,',','.') }}
</b>

dan pinjaman diangsur

<b>
{{ $pengajuan->lama_angsuran }}
</b>

bulan dengan jumlah angsuran sebesar

@php
    $jumlahPengajuan = $pengajuan->jumlah_pengajuan;
    $bunga = $pengajuan->skemaPinjaman->bunga;
    $lamaAngsuran = $pengajuan->lama_angsuran;

    $totalBunga = $jumlahPengajuan * ($bunga / 100) * $lamaAngsuran;
    $totalPinjaman = $jumlahPengajuan + $totalBunga;
    $angsuranPerBulan = $totalPinjaman / $lamaAngsuran;
@endphp

<b>
Rp {{ number_format($angsuranPerBulan, 0, ',', '.') }}
</b>

</p>

<p>
Demikian surat pengajuan ini, atas perkenan nya disampaikan terima kasih.
</p>

<div class="signature">

Hormat Kami,<br>
Pemohon

<br><br><br><br><br>

({{ $pengajuan->users->name }})

</div>

<div class="approval">

<table>

<tr>

<td class="text-center">

Mengetahui,<br>

Wadir II Bidang Umum & Keuangan

<br>

@if(($persetujuan['wadir']->status ?? null) == 'disetujui')
    <img src="{{ public_path('tanda_tangan/' . $pengurus['wadir']->tanda_tangan) }}" class="checks">
@else
    <img src="{{ public_path('images/check.png') }}" class="checks" style="visibility:hidden;">
@endif

</td>

<td class="text-center">

Menyetujui,<br>

Bendahara pengeluaran

<br>

@if(($persetujuan['bendahara']->status ?? null) == 'disetujui')
    <img src="{{ public_path('tanda_tangan/' . $pengurus['bendahara']->tanda_tangan) }}"
        class="checks">
@else
    <img src="{{ public_path('images/check.png') }}" class="checks" style="visibility:hidden;">
@endif

</td>

</tr>

<tr>

<td class="text-center">
    <span class="nama-ttd">
        {{ strtoupper($pengurus['wadir']->name ?? '-') }}
    </span>
    <br>
    NIP. {{ $pengurus['wadir']->nip ?? '-' }}
</td>

<td class="text-center">
    <span class="nama-ttd">
        {{ strtoupper($pengurus['bendahara']->name ?? '-') }}
    </span>
    <br>
    NIP. {{ $pengurus['bendahara']->nip ?? '-' }}
</td>

</tr>

</table>

</div>

</body>

</html>

<!DOCTYPE html>
<html>
<head>
<style>

body{
    font-family:"Times New Roman";
    font-size:15px;
    line-height:1.5;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 1px 0; /* Atas-bawah 2px, kiri-kanan 0 */
    line-height: 1.1;
}

.text-center{
    text-align:center;
}

.check{
    width:70px;
    height:70px;
}

</style>
</head>

<body>

<table style="width:100%; border:none;">
    <tr>
        <td>
            <img src="{{ public_path('images/logo-koperasii.png') }}"
                style="width:120px; height:120px;">
        </td>

        <td style="border:none; text-align:center;">

            <b style="font-size:18px;">
                KOPERASI KARYAWAN
                <br>
                POLITEKNIK NEGERI BANYUWANGI
            </b>

            <br>

            Jalan Raya Jember Km.13 Labanasem, Kabat Banyuwangi (68461)

            <br>

            Telepon/Fax : (0333) 636780

            <br>

            E-mail : poliwangi@poliwangi.ac.id Web Site : http://www.poliwangi.ac.id

        </td>

        <td style="width:120px; border:none;">
            &nbsp;
        </td>
    </tr>
</table>

<!-- Garis Pembatas -->
<div style="margin-top:8px; margin-bottom:18px;">
    <hr style="border:0; border-top:3px solid #000; margin:0;">
</div>

<div style="
    background:#d9d9d9;
    border:1px solid #000;
    text-align:center;
    font-weight:bold;
    font-size:13px;
">
    DATA PEMOHON KREDIT
</div>

<table>

<tr>
<td width="30%">Nama Pemohon</td>
<td width="3%">:</td>
<td>{{ $pengajuan->users->name }}</td>
</tr>

<tr>
<td>NIP/NIK/NIPPPK</td>
<td>:</td>
<td>{{ $pengajuan->users->nip }}</td>
</tr>

<tr>
<td>Unit Kerja</td>
<td>:</td>
<td>{{ $pengajuan->users->units->nama ?? '-' }}</td>
</tr>

<tr>
<td>Alamat Rumah</td>
<td>:</td>
<td>{{ $pengajuan->alamat }}</td>
</tr>

<tr>
<td>Jumlah Pinjaman</td>
<td>:</td>
<td>Rp {{ number_format($pengajuan->jumlah_pengajuan,0,',','.') }}</td>
</tr>

<tr>
<td>Telepon</td>
<td>:</td>
<td>{{ $pengajuan->no_hp ?? '-' }}</td>
</tr>

<tr>
<td>Nama Istri/Suami</td>
<td>:</td>
<td>{{ $pengajuan->nama_istri_suami ?? '-' }}</td>
</tr>

<tr>
<td>Status SK kerja</td>
<td>:</td>
<td>Kontrak berakhir pada {{ '-' }}</td>
</tr>

</table>

<div style="
    background:#d9d9d9;
    border:1px solid #000;
    text-align:center;
    font-weight:bold;
    font-size:13px;
    margin-top:2px;
">
    SURAT PERNYATAAN DAN KUASA
</div>

<div style="font-weight:bold;">Yang bertanda tangan di bawah ini Saya</div>

<table>

<tr>
<td width="30%">Nama Pemohon</td>
<td width="3%">:</td>
<td>{{ $pengajuan->users->name }}</td>
</tr>

<tr>
<td>NIP/NIK/NIPPPK</td>
<td>:</td>
<td>{{ $pengajuan->users->nip }}</td>
</tr>

<tr>
<td>Unit Kerja</td>
<td>:</td>
<td>{{ $pengajuan->users->units->nama ?? '-' }}</td>
</tr>

<tr>
<td>Alamat Rumah</td>
<td>:</td>
<td>{{ $pengajuan->alamat }}</td>
</tr>

<tr>
<td>Telepon</td>
<td>:</td>
<td>{{ $pengajuan->no_hp ?? '-' }}</td>
</tr>

</table>

<p align="justify" style="margin-bottom:0;">

Menyatakan menerima ketentuan pinjaman sebagaimana tertulis ini,
selanjutnya pinjaman tersebut menjadi pinjaman pribadi saya.

</p>

<ol style="margin-top:0;">

<li>
Pinjaman / Kredit tersebut akan diangsur dalam jangka waktu selama
<b>{{ $pengajuan->lama_angsuran }}</b>
bulan.
</li>

<li>
Atas pinjaman / kredit tersebut Saya bersedia dikenakan bunga (tetap)
<b>{{ $pengajuan->skemaPinjaman->bunga }}</b>
%
</li>

<li>

Untuk pelunasan fasilitas kredit tersebut, Saya memberikan kuasa penuh
yang tidak dapat dibatalkan / dicabut kepada Bendahara KKP guna mendebet gaji / 
tunjangan lainnya yang selanjutnya dibayarkan sebagai angsuran bulanan / pelunasan 
kredit yang saya peroleh dari KKP. Apabila saya keluar dari keanggotaan Koperasi, 
maka KKP berhak untuk melakukan pendebetan sebesar jumlah tunggakan yang menjadi
kewajiban saya, langsung dari gaji / benefit terakhir yang menjadi hak Saya sebagai anggota

</li>

<li>

Apabila seluruh kompensasi tunjangan masih belum mencukupi pelunasan kredit, 
Saya bersedia melunasi sisa kredit pinjaman di atas baik secara tunai atau 
dengan harta milik pribadi maupun keluarga

</li>

</ol>

<p align="justify" style="margin-bottom:0;">

Apabila saya melalaikan hal-hal yang telah Saya sebutkan di atas,
maka saya bersedia dituntut sesuai hukum yang berlaku.

</p>

<p align="justify" style="margin-top:0;">

Demikian surat pernyataan dan kuasa ini Saya buat dengan sebenar-benarnya 
dan digunakan sebagaimana mestinya.

</p>

<p style="margin-bottom:0;">
    Banyuwangi,
    {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
</p>

<table style="margin-top:0;">

<tr>

    <td class="text-center">

    Yang memberi pernyataan &
    <br>
    kuasa

    <br><br>

    <img src="{{ public_path('images/check.png') }}" class="check" style="visibility:hidden;">

    <br>

    <span>

    ( {{ ($pengajuan->users->name) }} )

    </span>

    </td>

<td class="text-center">

Mengetahui
<br>
Koord. Simpan Pinjam

<br><br>

<img src="{{ public_path('tanda_tangan/' . $pengurus['koordinator']->tanda_tangan) }}" class="check">

<br>

<span>

( {{ ($pengurus['koordinator']->name ?? '-') }} )

</span>

</td>

<td class="text-center">

Disetujui oleh

<br>

Ketua Koperasi

<br><br>

@if(($persetujuan['ketua']->status ?? null) == 'disetujui')
<img src="{{ public_path('tanda_tangan/' . $pengurus['ketua']->tanda_tangan) }}" class="check">
@else
<img src="{{ public_path('images/check.png') }}" class="check" style="visibility:hidden;">
@endif

<br>

<span>

( {{ ($pengurus['ketua']->name ?? '-') }} )

</span>

</td>

</tr>

</table>

<br>

<i>
NB : Form paling lambat disetor 1 minggu dari tanggal pengajuan.
</i>

</body>
</html>