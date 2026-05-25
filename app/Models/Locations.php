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


class Locations extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $guard = 'admin';
    protected $table ='locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'short_address',
        'latitude',
        'longitude',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
  

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
