<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store()
    {
        //return request()->all();
        $attributes = request()->validate([ //This will automaticaally redirect to the page that made the request if it doesn't succeed
            'name' => 'required|max:255',
            'username' => 'required|max:255|min:3|unique:users,username', //Username is a unique column, so validation should fail if the value provided is not unique
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'max:255', 'min:7'] //Can also validate like this and do things like Rule::unique('users', 'password')
        ]);

        $user = User::create($attributes); //Mass assignment

        auth()->login($user);

        session()->flash('success', 'Your account has been created'); //Adds to the session only for the next page load

        //return redirect('/')->with('success', 'Your account has been created'); Or you can do it all in one go, they do the same thing

        return redirect('/');
    }
}
