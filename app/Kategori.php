<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori'];
    public function kategori()
    {
        return $this->hasMany('App\Arsip');
    }
}