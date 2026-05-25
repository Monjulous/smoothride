<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use App\Models\Token;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_status',
        'img_status',
        'name',
        'lname',
        'mobile',
        'gender',
        'bio',
        'dob',
        'user_type',
        'email',
        'email_verified_at',
        'password',
        'image',
        'status',
        'is_verifyId',
        'is_verifyNumber',
        'is_verifyEmail',
        'profile_status',
        'latitude',
        'longitude',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    public function getRoleCodes()
    {
        $user = Auth::user();
        return $roles = Role::where('name',$user->getRoleNames())->get();
    }

    public function card()
    {
        return $this->hasMany(Card::class);
    }


    // app/Models/User.php

public function deviceTokens()
{
    return $this->hasMany(Token::class); 
}

}
