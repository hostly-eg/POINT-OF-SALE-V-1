<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function index()
    {
        $users = UserDetail::all();
        return view('users.index', compact('users'));
    }


    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'company' => 'required',
        ]);

        $user = UserDetail::create($request->all());

        return response()->json($user);
    }

    public function edit(UserDetail $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, UserDetail $user)
    {
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy(UserDetail $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
