<!DOCTYPE html>
<html>
<head>
    
    <title>Riwayat PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png" alt="Logo">
        <h1>Tokoman</h1>
        <h2>{{now()->format('d/m/Y').' '.now()->format('H:i')}}</h2>
    </div>
    <div class="chart">
        <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/chart.png" alt="Chart" style="width: 100%; height: 40%;">
    </div>
    <h2>Detail Riwayat</h2>
    <h3>Barang Masuk</h3>
    <table>
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jenis Riwayat</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataIn as $item)
            <tr>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->jenis_riwayat }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Barang Keluar</h3>
    <table>
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jenis Riwayat</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataOut as $item)
            <tr>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ 'Keluar' }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>