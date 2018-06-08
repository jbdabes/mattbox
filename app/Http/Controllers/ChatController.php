<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Models\Chat\Shout;
use App\Models\Chat\Command;
use App\Models\Chat\Censor;
use App\Models\Chat\Smiley;

use App\Models\User\User;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('notbanned')->except(['messages']);
    }

    public function messages() 
    {
        if (Auth::user()->banned === 1) {
            echo 'banned';
            exit;
        }

        $chat = Shout::whereNull('private')
        ->orderBy('id', 'desc')
        ->with('user.usergroup')
        ->with('user.shoutStyle')
        ->take(20)
        ->get();

        // Fixes null usergroup colors
        foreach ($chat as $message) {
            if ($message->user->usergroup->markup_before === null) {
                $message->user->usergroup->markup_before = '';
            }

            if ($message->user->usergroup->markup_after === null) {
                $message->user->usergroup->markup_after = '';
            }
        }

        return $chat;
    }

    public function privateMessage(User $user) 
    {
        $PM = Shout::where('private', Auth::user()->id)
        ->where('shouter', $user->id)
        ->orWhere(function($query) use ($user) {
            $query->where('shouter', Auth::user()->id)
            ->where('private', $user->id);
        })
        ->orderBy('id', 'desc')
        ->with('user.usergroup')
        ->with('user.shoutStyle')
        ->take(20)
        ->get();

        //Yet Another Fix for Usergroups
        foreach($PM as $PvM){
            if($PvM->user->usergroup->markup_before === null){
                $PvM->user->usergroup->markup_before = '';
            }
            if($PvM->user->usergroup->markup_after === null){
                $PvM->user->usergroup->markup_after = '';
            }
        }

        return $PM;
    }

    public function submit(Request $request) 
    {
        $time = time();

        $request->validate([
            'message' => 'required|max:1000|min:1',
        ]);

        $message = e($request->input('message'));
        $private = $request->input('private');

        $command = $this->commandHandler($message);

        $message = $command->message;
        $message = $this->censorHandler($message);
        

        if (trim($message) == '') {
            return $time;
        }

        $parsedown = new \Parsedown;
        $parsedown->setSafeMode(true);
        $message = $parsedown->line($message);

        try {
            $shout          = new Shout;
            $shout->message = $message;
            $shout->shouter = \Auth::user()->id;
            $shout->sys     = $command->isSys;
            if ($private && $private != -1) $shout->private = $private;
            $shout->save();
        } catch (\Exception $e) {
            // Fail silently for now.
        }

        Cache::forever('timer', $time);

        return $time;
    }

    public function edit(Request $request, Shout $shout) {
        $request->validate([
            'message' => 'required|max:1000|min:1',
        ]);

        $message = e($request->input('message'));

        $parsedown = new \Parsedown;
        $parsedown->setSafeMode(true);
        $message = $parsedown->line($message);
        
        if (!$shout->owns()) abort(405);
        $shout->message = $message;
        $shout->save();
        $time = time();
        Cache::forever('timer', $time);
        return $time;
    }

    public function delete(Shout $shout) {
        if (!$shout->owns()) abort(405);
        $shout->delete();
        $time = time();
        Cache::forever('timer', $time);
        return $time;
    }

    public function timer() {
        return Cache::get('timer');
    }

    public function smileys() {
        return Smiley::all();
    }
    

    private function commandHandler(&$message) {
        $command = Command::parse($message);

        if ($command == null) return $command;

        try {
            $className = '\\App\\Libraries\\Command\\' . ucwords($command->command) . 'Command';
            with(new $className)->run($command, $message);
        } catch (\Throwable $e) {
            // Don't do anything, just fail silently
        }

        return $command;
        
    }
    
    private function censorHandler($message){
        return Censor::parse($message); 
    }
}
