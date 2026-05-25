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


class Verify_id extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $guard = 'admin';
    protected $table = 'verify_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_date',
        'id_proff_status',
        'driving_licence_status',
        'vehicle_plate_status',
        'insurance_status',
        'id_proof_expdate',
        'driving_licence_expdate',
        'vehicle_plate_expdate',
        'insurance_expdate',
        'vehicle_rc_expdate',	
        'fitness_certificate_expdate',	
        'tourist_permit_expdate',	
        'driving_licence_tr_expdate',	
        'puc_expdate',
        'id_proof_renewdate',
        'driving_licence_renewdate',
        'vehicle_plate_renewdate',
        'insurance_renewdate',
        'id_proof',
        'insurance',
        'driving_licence',
        'vehicle_plate',
        'first_name',
        'last_name',
        'user_id',
        'type',
        'image',
        'phone_no',
        'email',
        'otp',
        'is_verifyId',
        'is_verifyNumber',
        'is_verifyEmail',
        'created_at',
        'updated_at',
        'vehicle_rc',	
        'fitness_certificate'	,
        'tax_receipt',
        'registration_slip'	,	
        'tourist_permit',
        'driving_licence_tr',	
        'puc',
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
