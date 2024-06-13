@extends('layouts.main')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h1>Produk</h1>
        <p>Daftar produk pada Toko Laros Cell</p>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <a type="button" class="btn btn-primary btn-md" href="{{ route('produk.create') }}" role="button">
                    <i class="fa fa-plus"></i> Tambah
                </a>
              </div>
            <table id="myTable" class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-start">
                    @foreach ($produk as $row)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$row->nama_produk}}</td>
                            <td>{{$row->kategori}}</td>
                            <td>Rp {{$row->harga_beli}}</td>
                            <td>Rp {{$row->harga_jual}}</td>
                            <td>{{$row->stok}}</td>
                            <td class="d-flex">
                                <a href="{{route('produk.edit', ['id' => $row->id])}}" type="button" class="btn btn-icon btn-warning mr-2" name="edit">
                                    <i class="text-white fa fa-edit" aria-hidden="true"></i>
                                </a>
                                <a href="{{route('produk.delete', ['id' => $row->id])}}}" type="button" class="btn btn-icon btn-danger" name="delete" onclick="if(!confirm('Yakin Akan Menghapus?')){return false}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('sweetalert::alert')

</section>


@endsection
