<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $query = Item::with('purchase');

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if (!empty($keyword)) {
            $query->where('item_name', 'like', '%' . $keyword . '%');
        }

        $items = $query->get();

        return view('index', [
            'items' => $items,
            'keyword' => $keyword,
            'tab' => 'recommend',
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $items = Item::keywordSearch($keyword)
            ->with('purchase')
            ->get();

        return view('index',[
            'items' => $items,
            'keyword' => $keyword,
            'tab' => 'recommend',
            ]);
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

    public function store(ExhibitionRequest $request)
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