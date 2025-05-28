@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Edit Arsip</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/arsip/update/'.$arsip->id_arsip) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <label>Nama Surat</label>
                            <input type="text" name="nama_surat" class="form-control" value="{{ $arsip->nama_surat }}" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ $arsip->kategori_id == $k->id ? 'selected' : '' }}  >
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                        </div>

                        <div class="form-group">
                            <label>File (biarkan kosong jika tidak diubah)</label>
                            <input type="file" name="file" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Jam</label>
                            <input type="time" name="jam" class="form-control" value="{{ $arsip->jam }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tempat</label>
                            <input type="text" name="tempat" class="form-control" value="{{ $arsip->tempat }}" >
                        </div>

                        <div class="form-group">
                            <label>Kegiatan</label>
                            <input type="text" name="kegiatan" class="form-control" value="{{ $arsip->kegiatan }}" >
                        </div>

                        <div class="form-group">
                            <label>Pejabat</label>
                            <input type="text" name="pejabat" class="form-control" value="{{ $arsip->pejabat }}" >
                        </div>

                        <div class="form-group">
                            <label>Ket.</label>
                            <textarea name="keterangan" class="form-control">{{ $arsip->keterangan }}</textarea>
                        </div>

                         <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="created_at" class="form-control" value="{{$arsip->created_at}}" required>
                            <!-- <textarea name="tanggal" class="form-control">{{ $arsip->tanggal }}</textarea> -->
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ url('/arsip') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
