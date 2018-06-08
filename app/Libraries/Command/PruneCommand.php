<?php

namespace App\Libraries\Command;

use App\Models\User\User;
use App\Models\Chat\Chat;

class PruneCommand extends \App\Libraries\Command\Command {

    public function run(\App\Models\Chat\Command $command)
    {
        $this->protect(2);
        if ($command->argument) $this->pruneUser($command);
        else                    $this->truncate($command);     
    }

    private function truncate($command)
    {
        Chat::truncate();

        $command->setMessage("pruned the shoutbox!", true);
    }

    private function pruneUser($command)
    {
        $user = User::where('name', $command->argument)
        ->orWhere('id', intval($command->argument))
        ->first();

        if (!$user) $command->setMessage('');

        $user->shouts()->delete();

        $command->setMessage("pruned all messages by " . $user->name, true);
    }

}