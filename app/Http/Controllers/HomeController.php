<?php

namespace App\Http\Controllers;
use App\Exports\LaporanExport;
use App\Arsip;
use App\Kategori;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function kategori(){
        $nama_kategori=Kategori::all();
        return view('Kategori.kategori',['nama_kategori'=>$nama_kategori]);
    }

    public function kategori_tambah()
    {
        return view('Kategori.tambah');
    }

    public function kategori_aksi(Request $data)
    {
        $data->validate(['nama_kategori' => 'required']);
        $nama_kategori = $data->nama_kategori;

        $cek = Kategori::where('nama_kategori', $nama_kategori)->first();

        if ($cek) {
            return redirect('/kategori')->with("error", "Kategori dengan nama '$nama_kategori' sudah ada.");
        }

        Kategori::insert(['nama_kategori' => $nama_kategori]);
        return redirect('/kategori')->with("sukses","Kategori Arsip berhasil tersimpan");
    }

    public function kategori_edit($id)
    {
        $nama_kategori = Kategori::find($id);
        return view('Kategori.edit',['nama_kategori' => $nama_kategori]);
    }
    
    public function kategori_update($id, Request $data){
        // Form Validasi
        $data->validate(['nama_kategori'=>'required']);


        $nama_kategori = $data->nama_kategori;

        // Update kategori
        $nama_kategori = Kategori::find($id);
        $nama_kategori->nama_kategori = $data->nama_kategori;
        $nama_kategori->save();

        return redirect('/kategori')->with("sukses", "Kategori Arsip berhasil diubah");
    }
    
    public function kategori_hapus($id){
        $kategori=Kategori ::find($id);
        $kategori->delete();
        return redirect('/kategori')->with("sukses","Kategori Arsip berhasil dihapus");
    }

    public function arsip(){
        // mengambil data transaksi
        $arsip=Arsip::all();

        // passing data transaksi ke view transaksi.blade.php
        return view('Arsip.arsip',['arsip'=>$arsip]);
    }

    public function arsip_tambah()
    {
        $nama_kategori = Kategori::all();
        return view('Arsip.tambah', compact('nama_kategori'));
    }

    public function arsip_aksi(Request $request)
{
    // Validasi inputan
    $this->validate($request, [
        'nama_surat' => 'required',
        'kategori_id' => 'required|exists:kategori,id',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
        'jam' =>'required',
        'tempat'=>'required',
        'kegiatan'=>'required',
        'pejabat'=>'required',
        'keterangan' => 'nullable',
        'created_at'=>'required'
    ]);

    // Ambil file dari input
    $file = $request->file('file');

    // Buat nama file unik
    $nama_file = time() . '_' . $file->getClientOriginalName();

    // Tentukan folder tujuan upload
    $tujuan_upload = public_path('data_file');

    // Upload file ke folder tujuan
    $file->move($tujuan_upload, $nama_file);

    // Simpan data ke database
    Arsip::create([
        'nama_surat' => $request->nama_surat,
        'kategori_id' => $request->kategori_id,
        'file' => $nama_file,
        'jam' =>$request->jam,
        'tempat'=>$request->tempat,
        'kegiatan'=>$request->kegiatan,
        'pejabat'=>$request->pejabat,
        'keterangan' => $request->keterangan,
        'created_at'=>$request->created_at
    ]);

    // Redirect dengan pesan sukses
    return redirect('/arsip')->with('sukses', 'Data arsip berhasil ditambahkan!');
}

    public function arsip_edit($id)
{
    $arsip = Arsip::findOrFail($id);
    $kategori = Kategori::all();
    return view('Arsip.edit', compact('arsip', 'kategori'));
}

public function arsip_update(Request $request, $id)
{
    $arsip = Arsip::findOrFail($id);
    $arsip->nama_surat = $request->nama_surat;
    $arsip->kategori_id = $request->kategori_id;
    $arsip->jam = $request->jam;
    $arsip->tempat = $request->tempat;
    $arsip->kegiatan = $request->kegiatan;
    $arsip->pejabat = $request->pejabat;
    $arsip->keterangan = $request->keterangan;
    $arsip->created_at = $request->created_at;

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $nama_file = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('data_file'), $nama_file);
        $arsip->file = $nama_file;
    }

    $arsip->save();

    return redirect('/arsip')->with('sukses', 'Data arsip berhasil diperbarui.');
}

    public function arsip_hapus($id)
    {
        // Ambil data transaksi berdasarkan id, kemudian hapus
        $arsip = Arsip::find($id);
        $arsip ->delete();

        // Alihkan halaman kembali ke halaman transaksi sambil mengirim pesan notifikasi
        return redirect('arsip')->with("sukses","Arsip berhasul dihapus");
    }

    // Lihat File
    public function arsip_file($filename){
    $path = public_path('data_file/' . $filename);

    // Cek apakah file ada
    if (!File::exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    // Ambil file dan kirim sebagai response download/view
    return response()->file($path);
    }

    public function laporan()
    {
    // data kategori
    $kategori = Kategori::all();
    // passing data kategori ke view laporan
    return view('Laporan.laporan',['kategori' => $kategori]);
    }

   public function laporan_hasil(Request $request)
    {
    $request->validate([
        'dari' => 'required',
        'sampai' => 'required'
    ]);

    // Ambil data filter
    $dari = $request->dari;
    $sampai = $request->sampai;
    $kategori = $request->kategori;

    // Ambil semua data kategori
    $kategoriData = Kategori::all();

    // Cek kategori
    if ($kategori == 'semua') {
        // Semua kategori
        $arsip = Arsip::with('kategori')
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('id_arsip', 'desc')
            ->get();
    } else {
        // Filter berdasarkan kategori
        $arsip = Arsip::with('kategori')
            ->where('kategori_id', $kategori)
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('id_arsip', 'desc')
            ->get();
    }

    return view('Laporan.hasil', [
        'arsip' => $arsip,
        'kategori' => $kategoriData,
        'dari' => $dari,
        'sampai' => $sampai,
        'kat' => $kategori, // untuk dipakai di view
        ]);
    }

    public function laporan_print(Request $request)
{
    $dari = $request->dari;
    $sampai = $request->sampai;
    $kategori = $request->kategori;

    if ($kategori == 'semua') {
        $arsip = Arsip::with('kategori')
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('id_arsip', 'desc')
            ->get();

        $nama_kategori = 'Semua';
    } else {
        $arsip = Arsip::with('kategori')
            ->where('kategori_id', $kategori)
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('id_arsip', 'desc')
            ->get();

        $kategoriModel = Kategori::find($kategori);
        $nama_kategori = $kategoriModel ? $kategoriModel->nama_kategori : 'Tidak Diketahui';
    }

    return view('Laporan.print', [
        'arsip' => $arsip,
        'dari' => $dari,
        'sampai' => $sampai,
        'kategori' => $nama_kategori, // Kirim nama kategori
    ]);
}


    public function laporan_excel(Request $request)
{
    $dari = $request->dari;
    $sampai = $request->sampai;
    $kategori = $request->kategori;

    if ($kategori == 'semua') {
        $arsip = Arsip::with('kategori')
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('created_at', 'asc') // sorting by date agar sheet berurutan
            ->get();

        $nama_kategori = 'Semua';
    } else {
        $arsip = Arsip::with('kategori')
            ->where('kategori_id', $kategori)
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->orderBy('created_at', 'asc')
            ->get();

        $kategoriModel = Kategori::find($kategori);
        $nama_kategori = $kategoriModel ? $kategoriModel->nama_kategori : 'Tidak Diketahui';
    }

    // Kirim semua data ke LaporanExport multi-sheet
    return Excel::download(new LaporanExport($arsip, $dari, $sampai, $nama_kategori), 'Laporan_Arsip.xlsx');
}



}