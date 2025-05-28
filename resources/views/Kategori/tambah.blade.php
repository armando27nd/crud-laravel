@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Tambah Kategori
                    <a href="{{ url('/kategori') }}" class="float-right btn btn-sm btnprimary">Kembali</a>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('/kategori/aksi') }}">
                        @csrf
                        <div class="form-group">
                            <label>Jenis Surat</label>
                            <input type="text" name="nama_kategori" class="form-control">
                            @if($errors->has('nama_kategori'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('nama_kategori') }}</strong>
                            </span>
                            @endif

                        </div>
                        <input type="submit" class="btn btn-primary" value="Simpan">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection