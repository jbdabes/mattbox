<?php

namespace App\Libraries\Command;

use App\Models\User\User;

class UnbanCommand extends \App\Libraries\Command\Command {

    public function run(\App\Models\Chat\Command $command)
    {
        $this->protect(2);
        
        if ($command->argument) $this->unbanUser($command);
        else                    $command->setMessage('');
    }

    private function unbanUser($command)
    {
        $user = User::where('name', $command->argument)
        ->orWhere('id', intval($command->argument))
        ->first();

        if (! $user || ! $user->banned) $command->setMessage('');
        
        $user->banned = 0;

        $user->save();

        $command->setMessage($user->name . " has been unbanned!", true);

    }

}