@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Data Arsip
                    <a href="{{ url('/arsip/tambah') }}" class="float-right btn btn-sm btn-primary">Input Arsip</a>
                </div>
                <div class="card-body">

                    @if(Session::has('sukses'))
                    <div class="alert alert-success">
                        {{ Session::get('sukses') }}
                    </div>
                    @endif

                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nama Surat</th>
                                <th>Kategori</th>
                                <th>File</th>
                                <th>Jam</th>
                                <th>Tempat</th>
                                <th>Kegiatan</th>
                                <th>Pejabat</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th width="25%" class="text-center">OPSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach($arsip as $a)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{$a->nama_surat}}</td>
                                <td>{{ $a->kategori ? $a->kategori->nama_kategori : 'Kategori tidak ditemukan' }}</td>
                                <td>
                                    @if($a->file)
                                        <a href="{{ url('arsip_file/'.$a->file) }}" target="_blank">Lihat</a>
                                    @else
                                        Tidak ada
                                    @endif
                                </td>
                                <td>{{$a->jam}}</td>
                                <td>{{$a->tempat}}</td>
                                <td>{{$a->kegiatan}}</td>
                                <td>{{$a->pejabat}}</td>
                                <td>{{$a->keterangan}}</td>
                                <td>{{$a->created_at}}</td>
                                <td class="text-center">
                                    <a href="{{ url('/arsip/edit/'.$a->id_arsip) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ url('/arsip/hapus/'.$a->id_arsip) }}"
                                        class="btn btn-sm btn-primary">Hapus</a>
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
@endsection