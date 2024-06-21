@extends('layouts.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center">
                <div class="col-6 text-center">
                    <h1>Tambah Produk</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <form id="form" action="{{ route('produk.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama Produk</label>
                                    <input id="namaProduk" name="nama_produk" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nama produk" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Harga Beli</label>
                                    <input id="harga_beli" name="harga_beli" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Harga beli produk" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Harga Jual</label>
                                    <input id="harga_jual" name="harga_jual" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Harga jual produk" required/>
                                </div>
                                <div class="">
                                  <label for="exampleFormControlSelect1" class="form-label">Kategori</label>
                                  <select id="kategori" name="kategori" class="form-control" id="exampleFormControlSelect1" aria-label="Default select example" required>
                                    <option selected>Piih kategori produk</option>
                                    <option value="kartu">Kartu</option>
                                    <option value="saldo">Saldo</option>
                                    <option value="vocher">Vocher</option>
                                    <option value="aksessoris">Aksessoris</option>
                                  </select>
                                </div>
                                <div class="invisible">
                                    <input value="0" id="stok" name="stok" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Harga produk" required/>
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="mr-4">
                                        <a class="btn btn-secondary" href="{{route('produk')}}">Batal</a>
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
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
