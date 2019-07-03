<?php

namespace App\Http\Controllers\Admin;

use App\Language;
use App\User;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ProfilesController extends Controller
{
    public function edit($value='')
    {
        $user = Auth::user();

        if ($user) {
            return view('backend.profile.edit', [
                'user'      => $user,
            ]);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'email' => Rule::unique('users')->ignore(Auth::user()->id)
        ],[
            'name.required' => 'Ім`я обов`язкове до заповнення',
            'email.required' => 'Email обов`язкове до заповнення',
            'email.unique' => 'Цей email вже є у базі',
        ]);

        if($request->password) {
            $request->validate([
                'old_password' => 'required|password:' . Auth::user()->password,
                'password' => ['required', 'string', 'min:6', 'confirmed'],

            ],[
                'old_password.required' => 'Поточний пароль не заповнено',
                'old_password.old_password' => 'Поточний пароль не співпадає',
                'password.confirmed' => 'Паролі не співпадають',
            ]);

            Auth::user()->password = Hash::make($request->password);

        }

        Auth::user()->email = $request->email;
        Auth::user()->name = $request->name;

        Auth::user()->save();

        return redirect()
            ->route('admin.profile.edit')->with('success', 'Дані оновлено' );

    }

}