<?php

namespace App\Libraries\Command;

class Command {
    public function protect(int $type)
    {
        if (\Auth::user()->usergroup_id !== $type) {
            exit;
        }
    }

    public function only(array $users) {
        if (!in_array(\Auth::user()->id, $users)) {
            exit;
        }
    }
}