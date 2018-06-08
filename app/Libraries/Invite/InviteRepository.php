<?php

namespace App\Libraries\Invite;

use App\Models\Invite\Invite;
use App\Models\User\User;

class InviteRepository {
    protected $viewData = [];

    public function index()
    {
        $userID = \Auth::user()->id;

        $this->viewData['invites'] = with(new Invite)->getAllInvitesCreated($userID);
        $this->viewData['canCreate'] = with(new User)->canCreateNewInvites($userID);

        return view('invites.index', $this->viewData);
    }

    public function create()
    {
        $userID = \Auth::user()->id;

        if (with(new User)->canCreateNewInvites($userID)) {
            with(new Invite)->createNewInvite($userID);
            with(new User)->deductInviteCount($userID, 1);
        }

        return redirect()->route('invites.index');
    }

    public function accept($token)
    {
        if (\Auth::user()) {
            return redirect()->route('home.index');
        }

        $invite = with(new Invite)->getInviteByCode($token);

        if ($invite) {
            if ($invite->used === 0) {
                $this->viewData['token'] = $token;

                return view('invites.accept', $this->viewData);
            }
        }

        return redirect()->route('home.index');
    }

    public function createAccount($request)
    {
        if (\Auth::user()) {
            return redirect()->route('home.index');
        }

        $this->validateCreateAccount($request);

        $user = with(new User)->createAccount(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );

        // Mark invite as used
        $inviteCode = $request->input('invite_code');
        with(new Invite)->markAsUsed($inviteCode, $user);

        return redirect()->route('home.index');
    }

    private function validateCreateAccount($request)
    {
        $rules = [
            'name'     => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];

        $messages = [
            'name.unique'  => 'This username has already been taken.',
            'email.unique' => 'This email has already been taken.',
        ];

        $request->validate($rules, $messages);
    }
}