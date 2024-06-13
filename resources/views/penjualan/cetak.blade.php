<!-- resources/views/transaksi/cetak.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="">
        <h3 class="text-center mb-4">Laporan Transaksi Penjualan Laros Cell</h3>
        <div>
            <p>Tanggal : {{ formatDate($tanggal_mulai) }} sd {{ formatDate($tanggal_akhir) }}</p>
            <p>Produk : {{ $nama_produk ? $nama_produk : 'Semua produk'}}</p>
            <p>Jumlah transaksi penjualan: {{ $jumlahPenjualan }}</p>
        </div>
        <table>
            <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>No</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Pembuat</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody class="text-start">
                @if ($penjualan->isEmpty())
                    <tr class="text-center">
                        <td colspan="8">
                            <span class="fw-bold">Tidak ada transaksi penjualan</span>
                        </td>
                    </tr>
                @else
                    @foreach ($penjualan as $row)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$row->produk->nama_produk}}</td>
                            <td>{{$row->no}}</td>
                            <td>{{$row->jumlah}}</td>
                            <td>{{formatDate($row->tanggal)}}</td>
                            <td>{{$row->user->username}}</td>
                            <td>Rp {{number_format($row->total, 0, ',', '.')}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-center">Jumlah total</td>
                    <td>Rp {{ number_format($jumlahTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
