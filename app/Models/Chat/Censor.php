<?php

namespace App\Models\Chat;

//use Illuminate\Database\Eloquent\Model;
use App\Models\Chat\User;

class Censor
{
    public $message;
    public static $bannedWords = array("fuck");
    
        
        
        public function __construct() {
        
    }
    
        public static function parse($message){
        $instance = new self;
            
        $instance->message = $message;
            foreach(self::$bannedWords as $censor){
                if(stripos($instance->message, $censor) !== false){
                    $instance->message = preg_replace("/$censor/i" , str_repeat("♥", strlen($censor)), $instance->message);
                }
            }
            return $instance->message;
        }
}
