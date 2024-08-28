<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class Pack extends Model
{
    use HasFactory;
    protected $table = "packs";

    protected $fillable = [
        'name',
        'price',
        'picture',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_pack";
    public $incrementing = false;

    public static function createPack($name, $price, $picture){
        foreach ($picture as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $base = "storage/images/".$imageName;
                self::create([
                    'name' => $name,
                    'price' => $price,
                    'picture' => $base,
                ]);
            Storage::disk('public')->put('images/' . $imageName, file_get_contents($image));
            break;
        }
    }

    public static function updatePack($id, $name, $price, $picture)
    {
        $pack = self::find($id);

        if (!$pack) {
            return false;
        }

        if ($picture) {
            $imageName = time() . '_' . $picture->getClientOriginalName();
            $base = "storage/images/" . $imageName;
            $pack->picture = $base;
            Storage::disk('public')->put('images/' . $imageName, file_get_contents($picture));
        }

        $pack->name = $name;
        $pack->price = $price;
        $pack->save();

        return true;
    }




}
