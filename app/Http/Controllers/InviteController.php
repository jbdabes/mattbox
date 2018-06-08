<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Invite\InviteRepository;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['accept', 'createAccount']);
    }

    public function index()
    {
        return with(new InviteRepository)->index();
    }

    public function create()
    {
        return with(new InviteRepository)->create();
    }

    public function accept($token)
    {
        return with(new InviteRepository)->accept($token);
    }

    public function createAccount(Request $request)
    {
        return with(new InviteRepository)->createAccount($request);
    }
}
