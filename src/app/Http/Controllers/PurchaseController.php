<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);

        $paymentMethods = PaymentMethod::all();

        $user = Auth::user()->load('profile');

        return view('purchase', compact('item', 'paymentMethods','user'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user()->load('profile');

        $shipping = session('shipping');

        if ($shipping) {
            $postal_code = $shipping['postal_code'];
            $address = $shipping['address'];
            $building = $shipping['building'];
        } else {
            $postal_code = optional($user->profile)->postal_code;
            $address = optional($user->profile)->address;
            $building = optional($user->profile)->building;
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'payment_methods_id' => $request->payment_methods_id,
            'postal_code' => $postal_code,
            'address' => $address,
            'building' => $building,
        ]);

        session()->forget('shipping');

        return redirect('/')
            ->with('message', '購入が完了しました');
    }


    public function edit($item_id)
    {
        $user = Auth::user()->load('profile');

        return view('address', compact('user', 'item_id'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'shipping' => [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        ]);

        return redirect("/purchase/{$item_id}");
    }
}