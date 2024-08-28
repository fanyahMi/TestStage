<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "users";

    protected $fillable = [
        'name',
        'first_names',
        'date_birth',
        'email',
        'passwords',
        'role',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_user";
    public $incrementing = false;

    // Attributs supplémentaires non liés à la table de base de données



    public static function check_login($email, $password){
        try {
            $result = DB::select('select * from users u where u.email = ? AND  u.passwords = ?', [$email,$password]);
            if( count($result) > 0)
                return $result[0];
            throw new \Exception('Adresse email ou mot de passe incorrect.');
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            throw $th;
        }
    }

    public static function createUser(array $userData)
    {
        $userData['role'] = $userData['role'] ?? 0;
        return self::create($userData);
    }

}
