<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

    protected $fillable =[
    'name',
    'email',
    'user_id',
    'phone',
    'amount',
    'order_id',
    'payment_id',
    'status'

    ];
}
