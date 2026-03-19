<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    public function index()
    {
        $query = Item::with('purchase');

    if (Auth::check()) {
        $query->where('user_id', '!=', Auth::id());
    }

    $items = $query->get();

    $tab = 'recommend';

    return view('index', compact('items','tab'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $items = Item::keywordSearch($keyword)
            ->with('purchase')
            ->get();

        return view('index', compact('items'));
    }

    public function detail($item_id)
    {
        $item = Item::with([
        'purchase',
        'comments.user.profile',
        'categories',
        'mylists'

    ])->findOrFail($item_id);

    $isInMylist = false;

    if (auth()->check()) {
        $isInMylist = $item->mylists()
            ->where('user_id', auth()->id())
            ->exists();
            }
            return view('detail', compact('item', 'isInMylist'));
        }

    public function create()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $path = $request->file('item_image')->store('items', 'public');
        $item = Item::create([
            'item_image' => $path,
            'condition' => $request->condition,
            'item_name' => $request->item_name,
            'brand_name' => $request->brand_name,
            'item_detail' => $request->item_detail,
            'price' => $request->price,
            'user_id' => $user->id,
            ]);

            $item->categories()->sync($request->category_id);

            return redirect('/');
    }
}
    // ストアのバリデーション
    // $request->validate([
    //     'item_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
    //     'condition_id' => ['required'],
    //     'item_name' => ['required'],
    //     'item_detail' => ['required'],
    //     'price' => ['required'],
    // ]);
