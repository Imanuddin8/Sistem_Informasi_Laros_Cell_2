<!-- resources/views/transaksi/cetak.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Penjualan</title>
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
            <p>Jumlah transaksi penjualan : {{ number_format($jumlahPenjualan, 0, ',', '.') }}</p>
            <p>Jumlah total : Rp {{ number_format($jumlahTotal, 0, ',', '.') }}</p>
        </div>
        <table>
            <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>No</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Pembuat</th>
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
                            <td>{{number_format($row->jumlah, 0, ',', '.')}}</td>
                            <td>Rp {{number_format($row->total, 0, ',', '.')}}</td>
                            <td>{{formatDate($row->tanggal)}}</td>
                            <td>{{$row->user->username}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
