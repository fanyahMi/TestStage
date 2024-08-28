<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";

    protected $fillable = [
        'name',
        'first_name',
        'sex',
        'phone',
        'email',
        'address',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_customer";
    public $incrementing = false;
}
