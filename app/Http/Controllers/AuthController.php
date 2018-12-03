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
        return redirect('saml2/login');
    }

    public function logout()
    {
        session()->flush();
        auth()->logout();
        return redirect('saml2/logout');
    }
}
