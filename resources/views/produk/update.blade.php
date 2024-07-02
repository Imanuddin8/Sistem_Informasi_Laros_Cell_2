@extends('layouts.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 text-center">
                    <h1>Edit Produk</h1>
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
                        <div class="card-header">
                            <form id="form" action="{{ route('produk.update', $produk->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama Produk</label>
                                    <input id="namaProduk" name="nama_produk" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nama produk" value="{{ $produk->nama_produk }}" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Harga Beli</label>
                                    <input value="{{ $produk->harga_beli }}" id="harga_beli" name="harga_beli" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Harga produk beli" oninput="formatNumber(this)" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Harga Jual</label>
                                    <input value="{{ $produk->harga_jual }}" id="harga_jual" name="harga_jual" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Harga produk jual" oninput="formatNumber(this)" required/>
                                </div>
                                <div class="mb-3">
                                  <label for="exampleFormControlSelect1" class="form-label">Kategori</label>
                                    <select id="kategori" name="kategori" class="form-control" id="exampleFormControlSelect1" aria-label="Default select example" required>
                                        <option selected value="{{ $produk->kategori }}">{{ $produk->kategori }}</option>
                                        <option value="kartu">Kartu</option>
                                        <option value="saldo">Saldo</option>
                                        <option value="vocher">Vocher</option>
                                        <option value="aksessoris">Aksessoris</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <div class="mr-4">
                                        <a class="btn btn-secondary" href="{{route('produk')}}">Batal</a>
                                    </div>
                                    <div>
                                      <button type="submit" name="update" class="btn btn-warning">Edit</button>
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
    </section>
    <!-- /.content -->
@endsection
