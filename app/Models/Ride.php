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


class Ride extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $guard = 'admin';
    protected $table ='add_rides';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rideid',
        'complete_status',
        'userid',
        'vehicle_id',
        'pick_location',
        'drop_location',
        'drop_lat',
        'drop_long',
        'pick_lat',
        'pick_long',
        'date',
        'time',
        'passenger_count',
        'stoppage',
        'small_bag',
        'hand_bag',
        'oversize_bag',
        'price',
        'instruction',
        'instant_booking',
        'music',
        'smoking_allowed',
        'pets_allowed',
        'backsheet',
        'verified_profile',
        'delete_status',
        'created_at',
        'updated_at',
        'pending_small_bag', 
        'pending_hand_bag',   
        'pending_regular_bag',   
        'pending_oversize_bag',   
        'ride_type', 
        'ride_status',  
        'paid_status',   
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     // 'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
    

    // public function getRoleCodes()
    // {
    //     $user = Auth::user();
    //     return $roles = Role::where('name',$user->getRoleNames())->get();
    // }

    // public function card()
    // {
    //     return $this->hasMany(Card::class);
    // }
}
