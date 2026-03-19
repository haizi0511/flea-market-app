<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $items = $user->purchases()->with('item')->get();
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

    public function store(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
        ]);

        $user->profile()->create([
            'postal_code'   => $request->postal_code,
            'address'       => $request->address,
            'building'      => $request->building,
            'profile_image' => $request->profile_image,
            ]);

        return redirect('/');
    }

        public function update(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->name,
        ]);

        $user->profile()->update([
            'postal_code'   => $request->postal_code,
            'address'       => $request->address,
            'building'      => $request->building,
            'profile_image' => $request->profile_image,
        ]);

        return redirect('/');
    }
}
