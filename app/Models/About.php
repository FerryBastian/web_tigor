<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'profile_image',
        'contact_info',
    ];
}
