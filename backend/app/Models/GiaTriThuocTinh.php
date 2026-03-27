<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaTriThuocTinh extends Model
{
    protected $table      = 'giatri_thuoctinh';
    protected $primaryKey = 'id_giatri';
    public $timestamps    = false;

    protected $fillable = [
        'id_thuoctinh',
        'giatri',
        'trangthai',
    ];

    // giá trị thuộc về thuộc tính
    public function thuocTinh()
    {
        return $this->belongsTo(ThuocTinh::class, 'id_thuoctinh', 'id_thuoctinh');
    }
}