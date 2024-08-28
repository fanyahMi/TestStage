<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Axe extends Model
{
    use HasFactory;
    protected $table = "axe";

    protected $fillable = [
        'desc',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_axe";
    public $incrementing = false;
}
