<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Mylist;
use Illuminate\Http\Request;


class MylistController extends Controller
{
    public function mylist(Request $request)
    {
        $keyword = $request->keyword;

        if (!Auth::check()) {
            $items = collect();
        } else {

            $items = Auth::user()
                ->mylists()
                ->with('item.purchase')
                ->get()
                ->pluck('item');
        }

        if (!empty($keyword)) {
            $items = $items->filter(function ($item) use ($keyword) {
                return strpos($item->item_name, $keyword) !== false;
            });
        }

        return view('index', [
            'items' => $items,
            'keyword' => $keyword,
            'tab' => 'mylist',
        ]);
    }

    public function store($item_id)
    {
        $exists = Mylist::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->exists();

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
