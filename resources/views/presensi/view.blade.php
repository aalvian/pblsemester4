@extends('master.main')
@section('title', 'Presensi')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800 mb-4">Data Presensi</h1>


        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nim</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Bukti Kehadiran</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($dtPresensi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->divisi }}</td>
                                    <td>{{$item->tanggal}}</td>

                                    <td>
                                    @if ($item->bukti)
                                <img src="{{ asset('storage/buktiPresensi/' . $item->bukti) }}" alt="Gambar tidak ada" style="max-width: 200px; max-height: 200px;">
                                @else
                                Tidak ada bukti
                                @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    {{-- sweet alert --}}
    @include('sweetalert::alert')

@endsection
