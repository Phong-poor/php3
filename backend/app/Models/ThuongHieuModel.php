<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuongHieuModel extends Model
{
      public $timestamps = false;
    protected $table = 'thuonghieu';
    protected $primaryKey = 'id_thuonghieu';

    protected $fillable = 
    [
        'ten_thuonghieu'
    ];
}
