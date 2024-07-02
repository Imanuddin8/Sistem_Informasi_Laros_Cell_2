@extends('layouts.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 text-center">
                    <h1>Tambah User</h1>
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
                            <form id="form" action="{{ route('user.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                    <input name="nama" id="nama" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nama User" required/>
                                  </div>
                                  <div class="mb-3">
                                      <label for="exampleFormControlInput1" class="form-label">Username</label>
                                      <input name="username" id="username" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Username user" required/>
                                  </div>
                                  <div class="mb-3">
                                      <label for="exampleFormControlInput1" class="form-label">Password</label>
                                      <input name="password" id="password" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Password user" required/>
                                  </div>
                                  <div class="mb-3">
                                    <label for="exampleFormControlSelect1" class="form-label">Posisi</label>
                                      <select name="role" id="role" class="form-control" id="exampleFormControlSelect1" aria-label="Default select example" required>
                                          <option selected>Piih posisi user</option>
                                          <option value="admin">Admin</option>
                                          <option value="karyawan">karyawan</option>
                                      </select>
                                  </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="mr-4">
                                        <a class="btn btn-secondary" href="{{route('user')}}">Batal</a>
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
