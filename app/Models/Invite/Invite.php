<?php

namespace App\Models\Invite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User\User;

class Invite extends Model
{
    use SoftDeletes;
    
    protected $table = 'invites';
    protected $dates = ['deleted_at'];

    /**
     * Relations
     */
    
    public function invitee()
    {
        return $this->hasOne(User::class, 'id', 'used_by');
    }

    /**
     * Attributes
     */
    
    public function getDateCreatedAttribute()
    {
        $created = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('F jS, Y H:i');

        return $created;
    }

    public function getInviteeNameAttribute()
    {
        if ($this->invitee) {
            return $this->invitee->name;
        }

        return 'N/A';
    }

    /**
     * Functions
     */

    public function getAllInvitesCreated($userID)
    {
        $invites = self::where('created_by', '=', $userID)
                       ->get();

        return $invites;
    }

    public function createNewInvite($userID)
    {
        $invite = new self;

        $invite->code       = sha1(time() . config('app.key'));
        $invite->created_by = $userID;
        $invite->used       = 0;
        $invite->used_by    = null;

        $invite->save();
    }

    public function getInviteByCode($token)
    {
        $invite = self::where('code', '=', $token)
                      ->first();

        return $invite;
    }

    public function markAsUsed($inviteCode, User $user)
    {
        $invite = self::where('code', '=', $inviteCode)
                      ->first();

        if ($invite) {
            $invite->used    = 1;
            $invite->used_by = $user->id;

            $invite->save();

            return true;
        }

        return false;
    }
}
