<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPack extends Model
{
    use HasFactory;

    protected $table = "detail_packs";


    protected $fillable = [
        'pack_id',
        'product_id',
        'quantity_product',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_detail_pack";
    public $incrementing = false;
}
