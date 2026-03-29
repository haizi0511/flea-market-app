<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $purchases = $user->purchases()->with('item')->get();
            $items = $purchases->pluck('item');
        }
        else {
            $items = $user->items()->get();
        }

        return view('mypage', compact('items', 'tab','user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;
        return view('profile', compact('user','profile'));
    }

    public function store(ProfileRequest $request)
    {
        $user = auth()->user();

        $path = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile', 'public');
        }

        $user->update([
            'name' => $request->name,
            'profile_completed' => 1,
        ]);

        $user->profile()->create([
            'postal_code'   => $request->postal_code,
            'address'       => $request->address,
            'building'      => $request->building,
            'profile_image' => $path,
        ]);

        return redirect('/');
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $path = $user->profile->profile_image;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile', 'public');
        }

        $user->update([
            'name' => $request->name,
            'profile_completed' => 1,
        ]);

        $user->profile()->update([
            'postal_code'   => $request->postal_code,
            'address'       => $request->address,
            'building'      => $request->building,
            'profile_image' => $path,
        ]);

        return redirect('/');
    }

}
