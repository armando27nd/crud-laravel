@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Tambah Arsip</div>
                <div class="card-body">
                    @if(session('sukses'))
                        <div class="alert alert-success">{{ session('sukses') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('/arsip/aksi') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>NAMA SURAT</label>
                            <input type="text" class="form-control" name="nama_surat" required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($nama_kategori as $nk)
                                    <option value="{{ $nk->id }}">{{ $nk->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>UPLOAD FILE (PDF, DOCX, XLSX, JPG, PNG)</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>JAM</label>
                            <input type="time" name="jam" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>TEMPAT</label>
                            <!-- <input type="text" name="tempat" class="form-control" required> -->
                            <textarea class="form-control" name="tempat" ></textarea>
                        </div>

                        <div class="form-group">
                            <label>KEGIATAN</label>
                            <!-- <input type="kegiatan" name="kegiatan" class="form-control" required> -->
                            <textarea class="form-control" name="kegiatan" ></textarea>

                        </div>

                        <div class="form-group">
                            <label>PEJABAT YANG HADIR</label>
                            <!-- <input type="pejabat" name="pejabat" class="form-control" required> -->
                            <textarea class="form-control" name="pejabat" ></textarea>
                        </div>

                        <div class="form-group">
                            <label>KET.</label>
                            <textarea class="form-control" name="keterangan" ></textarea>
                        </div>

                           <div class="form-group">
                            <label>TANGGAL</label>
                            <input type="date" class="form-control" name="created_at" required>
                            <!-- <textarea class="form-control" name="created_at" required></textarea> -->
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url('/arsip') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
