@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Filter Laporan Arsip
                </div>
                <div class="card-body">
                    <form method="get" action="{{ url('/laporan/hasil') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dari Tanggal</label>
                                    <input type="date" name="dari" class="form-control" value="{{ $dari }}">
                                    @if($errors->has('dari'))
                                        <span class="text-danger">{{ $errors->first('dari') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
                                    @if($errors->has('sampai'))
                                        <span class="text-danger">{{ $errors->first('sampai') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control" name="kategori">
                                        <option value="semua">- Semua Kategori</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id }}" {{ $kat == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary mt-4" value="Tampilkan">
                            </div>
                        </div>
                    </form>

                    <hr>

                    <a target="_blank" href="{{ url('/laporan/print?dari=' . $dari . '&sampai=' . $sampai . '&kategori=' . $kat) }}" class="btn btn-secondary">Print</a>
                    <a target="_blank" href="{{ url('/laporan/excel?dari=' . $dari . '&sampai=' . $sampai . '&kategori=' . $kat) }}" class="btn btn-success">Export Excel</a>

                    <table class="table table-bordered mt-4">
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
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($arsip as $a)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $a->nama_surat }}</td>
                                    <td>{{ $a->kategori ? $a->kategori->nama_kategori : '-' }}</td>
                                    <td>
                                        @if($a->file)
                                            <a href="{{ url('arsip_file/'.$a->file) }}" target="_blank">Lihat</a>
                                        @else
                                            Tidak ada
                                        @endif
                                    </td>
                                    <td>{{ $a->jam }}</td>
                                    <td>{{ $a->tempat }}</td>
                                    <td>{{ $a->kegiatan }}</td>
                                    <td>{{ $a->pejabat }}</td>
                                    <td>{{ $a->keterangan }}</td>
                                    <td>{{ date('d-m-Y', strtotime($a->created_at)) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data arsip yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
