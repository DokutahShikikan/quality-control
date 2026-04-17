<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->intended('/datasets')->with('success', 'Вы вошли в систему контроля качества данных.');
        }

        return redirect('/login')->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('/datasets');
    }
}
