<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class danhmucModel extends Model
{
    public $timestamps = false;
    protected $table = 'danhmuc';
    protected $primaryKey = 'id_danhmuc';

    protected $fillable = 
    [
        'ten_danhmuc',
        'trangthai'
    ];
}
