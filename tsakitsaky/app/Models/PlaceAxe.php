<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceAxe extends Model
{
    use HasFactory;
    protected $table = "place_axe";

    protected $fillable = [
        'axe_id',
        'place_id',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_place_axe";
    public $incrementing = false;
}
