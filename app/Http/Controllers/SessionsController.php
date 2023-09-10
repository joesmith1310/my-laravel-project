<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller //Use Laravel breeze for quick setup of authentication
{
    public function create()
    {
        return view('sessions.create');
    }
    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required']
        ]);

        if (auth()->attempt($attributes)) {
            session()->regenerate(); //Protects against session fixation attacks
            return redirect('/')->with('success', 'Welocme back!');
        }
        return back()
            ->withInput()
            ->withErrors(['email' => 'Your provided credentials could not be verified']);

        /*throw ValidationException::withMessages([
            'email' => 'Your provided credentials could not be verified' //This behaves the same way as if validate() failed above. Error messages are passed back and any form inputs from the previous request
        ]);*/
    }
    public function destroy()
    {
        auth()->logout();
        return redirect('/')->with('success', 'Goodbye!');
    }
}
