<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Overtrue\LaravelFollow\Traits\CanBeFavorited;

class Contact extends Model
{
    //use CanBeFavorited;
    use HasFactory;

    protected $table = "contact";

    protected $fillable = [
        'id',
        'name',
        'email',
        'subject',
        'message',
        
     ];

    public $timestamps=true;

    
    
}
