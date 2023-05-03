<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $menus = Menu::with('items')->select('name', 'id')->get();

        // session()->forget('cart');

        $filtered_menus = $menus->map(function($menu) {
            return [
                'name' => $menu->name,
                'id' => $menu->id,
                'items' => $menu->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'name_display' => $item->name_display,
                        'photo' => $item->photo 
                    ];
                })
            ];
        });

        return Inertia::render('order.Home', [
            'images' => [
                [
                    'id' => 1,
                    'src' => asset('images/slider_1.jpg')
                ],
                [
                    'id' => 2,
                    'src' => asset('images/slider_2.jpg')
                ],
                [
                    'id' => 3,
                    'src' => asset('images/slider_3.jpg')
                ],
                
            ],
            'menus' => $filtered_menus,
            'cart' => session('cart', []),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Store user cart on the session
     */
    public function cart(Request $request)
    {
        $quantity = $request->quantity ?? 1;
        $item = Item::findOrFail($request->id);

        $cart = session('cart', []);

        array_push($cart, [
            'id' => $item->id,
            'name'  => $item->name,
            'name_display' => $item->name_display,
            'price' => $item->price,
            'quantity' => $quantity
        ]);

        session()->put('cart', $cart);

    }
}
