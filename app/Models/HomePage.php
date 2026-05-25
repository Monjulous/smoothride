<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Overtrue\LaravelFollow\Traits\CanBeFavorited;

class HomePage extends Model
{
    //use CanBeFavorited;
    use HasFactory;

    protected $table = "home";

    protected $fillable = [
        'id',
        'banner_title',
        'banner_desc',
        'url1',
        'url2',
        'title',
        'desc1',
        'desc2',
        'desc3',
        'desc4',
        'contact_address',
        'contact_phone',
        'contact_email',
        'contact_fax',
        
     ];

    public $timestamps=true;

    
    
}
