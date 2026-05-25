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


class Request_ride extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    protected $table ='request_ride';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ride_id',
        'price',
        'users_id',
        'driver_id',
        'time',
        'reach_mint',
        'firebase_key',
        'status',
       'pick_latitude',
        'pick_longitude',
        'pick_locations',
        'drop_latitude',
        'drop_longitude',
        'drop_locations',
        'date',
        'created_at',
        'updated_at'
    ];

}
