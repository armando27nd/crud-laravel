<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Laporan Arsip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .kop {
            text-align: center;
            margin-bottom: 10px;
        }
        .kop img {
            float: left;
            width: 80px;
            height: auto;
            margin-right: 10px;
        }
        .kop h2, .kop h3, .kop p {
            margin: 0;
            line-height: 1.4;
        }
        hr {
            border: 1px solid black;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .header-info {
            margin-top: 20px;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="kop">
        <img src="{{ public_path('logo.png') }}" alt="Logo"> {{-- Ganti dengan logo dinas --}}
        <div style="text-align: center;">   
            <h2>PEMERINTAH KABUPATEN TANGERANG</h2>
            <h3>DINAS TENAGA KERJA</h3>
            <p>Jl. Parahu RT/RW. 05/01, Desa Parahu, Kec. Sukamulya, Kab. Tangerang</p>
            <p>Kode Pos 15612 | Telepon 021-59433197 | Email: disnaker@tangerangkab.go.id</p>
        </div>
    </div>
    <hr>

    <h4 style="text-align: center; text-decoration: underline;">JADWAL HARIAN</h4>
    <p><strong>DINAS:</strong> Dinas Tenaga Kerja Kab. Tangerang</p>
    <p><strong>HARI/TANGGAL:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    <p><strong>Periode:</strong> {{ $dari }} s/d {{ $sampai }}</p>
    <p><strong>Kategori:</strong> {{ $kategori == 'semua' ? 'Semua' : $kategori }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th style="width: 80px;">JAM</th>
                <th>KEGIATAN</th>
                <th>TEMPAT</th>
                <th>PEJABAT YANG HADIR</th>
                <th>KET.</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($arsip as $a)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $a->jam }}</td>
                    <td>
                        {{ $a->kegiatan }}
                        <br><small><strong>{{ $a->nama_surat }}</strong></small>
                    </td>
                    <td>{{ $a->tempat }}</td>
                    <td>{{ $a->pejabat }}</td>
                    <td>{{ $a->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
