<?php

namespace App\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

use App\Models\Usergroup\Usergroup;
use App\Models\Chat\Chat;
use App\Models\Chat\ShoutStyle;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Functions
     */
    
    public function usergroup() {
        return $this->hasOne(Usergroup::class, 'id', 'usergroup_id')->select(['id', 'title', 'markup_before', 'markup_after']);
    }

    public function shouts() {
        return $this->hasMany(Shout::class, 'shouter', 'id');
    }

    public function shoutStyle() {
        return $this->hasOne(ShoutStyle::class, 'id', 'shout_style_id')->select(['id', 'color', 'font', 'bold', 'italic', 'underline']);
    }
    
    public function canCreateNewInvites($userID)
    {
        $user = self::find($userID);

        if ($user) {
            if ($user->invite_codes > 0) {
                return true;
            }
        }

        return false;
    }

    public function deductInviteCount($userID, $count = 1)
    {
        $user = self::find($userID);

        if ($user) {
            if ($user->invite_codes > 0) {
                $user->invite_codes = $user->invite_codes - 1;

                $user->save();
            }
        }
    }

    public function createAccount($name, $email, $password)
    {
        $user = new self;

        $user->name     = $name;
        $user->email    = $email;
        $user->password = Hash::make($password);

        $user->save();

        return $user;
    }
}
