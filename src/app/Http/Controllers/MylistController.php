<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mylist;

class MylistController extends Controller
{
    public function mylist()
    {
        if (!Auth::check()) {
            $items = collect();   // 空のコレクション
        } else {
            $items = Auth::user()
                ->mylists()
                ->with('item.purchase')
                ->get()
                ->pluck('item');
        }

        $tab = 'mylist';

        return view('index', compact('items', 'tab'));
    }

    public function store($item_id)
    {
        // すでにいいねしてるかチェック
        $exists = Mylist::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->exists();

        // なければ登録
        if (!$exists) {
            Mylist::create([
                'user_id' => auth()->id(),
                'item_id' => $item_id,
            ]);
        }

        return back();
    }

    public function destroy($item_id)
    {
        Mylist::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}
