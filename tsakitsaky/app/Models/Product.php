<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'unit_id',
        'unitary_quantity',
        'cost_price',
        'product',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_product";
    public $incrementing = false;
}
