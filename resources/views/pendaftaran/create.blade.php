@extends('master.master1')
@section('title', 'Create Data')
@section('content')
<div class="container-fluid">
<h1 class="h3 mb-4 text-gray-800">Pendaftaran</h1>
<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"></h6>

        </div>
<div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <form action="{{ route('store-pendaftaran') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="nama_barang" style="font-weight: bold;"> Nama</label>
                            <input type="text" name="nama" id="nama_barang" class="form-control" placeholder="Masukkan Nama" required>
                        </div>
                        <div class="form-group">
                            <label for="stok" style="font-weight: bold;"> Nim</label>
                            <input type="text" name="nim" id="stok" class="form-control" placeholder="Masukkan Nim" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> Prodi</label>
                            <input type="text" name="prodi" id="tggl_masuk" class="form-control" placeholder="Masukkan prodi" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> Email</label>
                            <input type="text" name="email" id="tggl_masuk" class="form-control" placeholder="Masukkan email" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> Semester</label>
                            <input type="text" name="semester" id="tggl_masuk" class="form-control" placeholder="Masukkan semester" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> No telp</label>
                            <input type="text" name="no_telp" id="tggl_masuk" class="form-control" placeholder="Masukkan nomer telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> Divisi 1</label>
                            <input type="text" name="divisi_1" id="tggl_masuk" class="form-control" placeholder="Masukkan divisi 1" required>
                        </div>
                        <div class="form-group">
                            <label for="tggl_masuk" style="font-weight: bold;"> Divisi 2</label>
                            <input type="text" name="divisi_2" id="tggl_masuk" class="form-control" placeholder="Masukkan divisi 2" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>

                    </form>
                </table>
            </div>
        </div>
        </div>

</div>