<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $user_name = auth()->user()->name;
        // emotify('success', "Welcome back $user_name. Good luck!");
        // notify()->success('Welcome to Laravel Notify ⚡️'); or notify()->success('Welcome to Laravel Notify ⚡️', 'My custom title');
        // drakify('success'); or drakify('error');
        // smilify('success', 'You are successfully reconnected');
        // emotify('success', 'You are awesome, your data was successfully created');

        return view('home');
    }
}
