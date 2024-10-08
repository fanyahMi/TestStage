<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;
    protected $table = "product_units";

    protected $fillable = [
        'unite',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_unit";
    public $incrementing = false;

}
