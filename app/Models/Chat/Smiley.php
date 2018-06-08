<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class Smiley extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
