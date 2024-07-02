@extends('layouts.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 text-center">
                    <h1>Tambah Transaksi Penjualan</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="pb-8">
                        </div>
                        <div class="card-header">
                            <form id="form" action="{{ route('penjualan.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlSelect1" class="form-label">Nama Produk</label>
                                    <select name="produk_id" id="produk_id" class="form-control" id="exampleFormControlSelect1" aria-label="Default select example" required>
                                        @foreach($produk as $produk)
                                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                                {{ $produk->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">No</label>
                                    <input name="no" id="no" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nomor" required value="{{ old('no') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Jumlah</label>
                                    <input name="jumlah" id="jumlah" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Jumlah produk" required value="{{ old('jumlah') }}" oninput="formatNumber(this)">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Tanggal</label>
                                    <input name="tanggal" id="tanggal" type="date" class="form-control" id="exampleFormControlInput1" required value="{{ old('tanggal') }}">
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="mr-4">
                                        <a class="btn btn-secondary" href="{{route('penjualan')}}">Batal</a>
                                    </div>
                                    <div>
                                        <button type="submit" name="create" class="btn btn-primary">Tambah</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <script>
            function formatNumber(input) {
                // Hapus semua karakter non-digit
                let value = input.value.replace(/\D/g, '');
                // Format ulang angka dengan titik ribuan
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = value;
            }
        </script>
        <!-- /.container-fluid -->
        @include('sweetalert::alert')
    </section>
    <!-- /.content -->
@endsection
