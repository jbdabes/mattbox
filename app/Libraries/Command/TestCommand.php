<?php

namespace App\Libraries\Command;

class TestCommand extends \App\Libraries\Command\Command {

    public function run(\App\Models\Chat\Command $command)
    {
        // Do something cool.
        $this->protect(2);
        
        $command->setMessage('');
    }

}