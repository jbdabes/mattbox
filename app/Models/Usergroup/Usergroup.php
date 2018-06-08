<?php

namespace App\Models\Usergroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User\User;

class Usergroup extends Model
{
    use SoftDeletes;
    
    protected $table = 'usergroups';
    protected $dates = ['deleted_at'];

    public function users() {
        return $this->hasMany(User::class);
    }
}
