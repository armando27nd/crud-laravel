<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table='arsip';
    protected $primaryKey = 'id_arsip';
    // public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable=['nama_surat','kategori_id','file','jam','tempat','kegiatan','pejabat','keterangan','created_at'];
        public function kategori()
    {
        return $this->belongsTo('App\Kategori');
    }
}