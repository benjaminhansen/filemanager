<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Log In With Your Active Directory Account";
        return view('auth.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->attempt($request->only(['username', 'password']))) {
            return redirect()->intended('/');
        } else {
            return redirect()->back()->withMessage('<div class="alert alert-danger">Your username/password was incorrect!</div>')->withInput($request->all());
        }
    }

    public function logout()
    {
        session()->flush();
        auth()->logout();
        return redirect('login');
    }
}
