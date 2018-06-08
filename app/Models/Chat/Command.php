<?php

namespace App\Models\Chat;

use App\Models\User\User;

class Command
{
    public $command;

    public $argument;

    public $arguments = [];

    public $isValidFormat = false;

    public $isSys = false;

    public $message;

    public function __construct()
    {

    }

    public static function parse($message)
    {
        $instance = new self;

        $instance->message = $message;

        if (preg_match("#^(\/[a-z]+)([\s\w]+)*#i", $instance->message, $matches) === 0) return $instance;

        $instance->isValidFormat = true;

        $instance->command = trim(str_ireplace('/', '', $matches[1]));

        if (array_key_exists(2, $matches)) {
            $instance->addArguments(trim($matches[2]));
        }

        return $instance;
    }

    public function addArguments($arguments)
    {
        $args = explode(' ', $arguments);

        $this->argument  = $arguments;
        $this->arguments = $args;
    }

    public function setMessage($message, $isSys = false)
    {
        $this->message = $message;
        $this->isSys   = $isSys;
    }
}
