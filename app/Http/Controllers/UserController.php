<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()
        ->when(request('search'), function($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate()
        ->through(fn($user) => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'created' => Carbon::parse($user->created_at)->diffForHumans()
        ]);

        return Inertia::render('Users', [
            'users' => $users,
            'pageData' => [
                'userSearch' => request('search')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->validate([
            'name' => ['required'],
            'email'=> ['required', 'unique:users'],
            'password' => ['required']
        ]);

        User::create($user);

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Users', [
            'userForEdit' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            'email'=> ['required', Rule::unique('users')->ignore($id)],
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password) {
            $user->password = $request->password;
        }

        $user->update();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
    }
}
