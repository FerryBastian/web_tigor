<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{

    protected $table = 'about'; // <--- tambahkan ini

    protected $fillable = [
        'name',
        'bio',
        'profile_image',
        'contact_info',
    ];
}
