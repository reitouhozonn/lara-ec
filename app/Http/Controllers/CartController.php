<?php

namespace App\Http\Controllers;

use App\Mail\Buy;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            ->where('user_id', Auth::id())
            ->join('items', 'items.id', '=', 'cart_items.item_id')
            ->get();

        $subtotal = 0;
        foreach ($cartitems as $cartitem) {
            $subtotal += $cartitem->amount * $cartitem->quantity;
        }

        return view('cartitem.index', [
            'cartitems' => $cartitems,
            'subtotal' => $subtotal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'item_id' => $request->post('item_id'),
            ],
            [
                'quantity' => \DB::raw('quantity +' . $request->post('quantity')),
            ]
        );

        return redirect('/')->with('flash_massage', 'カートに追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartItem $cartitem)
    {
        $cartitem->quantity = $request->post('quantity');
        $cartitem->save();

        return redirect('cartitem')->with('flash_massage', 'カートを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartItem $cartitem)
    {
        $cartitem->delete();

        return redirect('cartitem')->with('flash_massage', 'カートから削除しました');
    }

    public function sendMail(Request $request, CartItem $cartItem)
    {
        Mail::to(Auth::user()->email)->send(new Buy());
        CartItem::where('user_id', Auth::id())->delete();
        // return view('cartitem');

        $request->flash();
        return redirect('cartitem')->with('flash_massage', '購入しました');
    }
}
