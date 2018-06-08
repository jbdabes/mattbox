<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

use App\Models\User\User;

class Shout extends Model
{
    protected $appends = ['can_edit'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'shouter')->select(['id', 'name', 'usergroup_id', 'shout_style_id']);
    }

    public function to() {
        return $this->hasOne(User::class, 'id', 'private')->select(['id', 'name', 'usergroup_id', 'shout_style_id']);  
    }

    public function owns() {
        return ($this->user->id == \Auth::user()->id || \Auth::user()->usergroup->id == 2)
                && !$this->sys;
    }

    public function getCanEditAttribute() {
        return $this->attributes['can_edit'] = $this->owns();
    }
}
