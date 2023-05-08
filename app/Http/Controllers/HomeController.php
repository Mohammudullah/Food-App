<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Enums\PaymentMethods;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;

use function Termwind\render;

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
    public function destroy(string $id): void
    {
        //
    }

    /**
     * Store user cart on the session
     */
    public function cart(Request $request): void
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

    /**
     * got to checkout with added cart items
     */
    public function checkout(Request $request)
    {
        $cartInfo = $this->cartTotal(collect(session('cart', [])));

        if($cartInfo['total'] <= 0) {
            return redirect(route('index'))->withErrors([
                'invalidCart' => 'Please add some item to cart for checkout',
            ]);
        }

        return Inertia::render('order.TheCheckout', [
            'cartInfo' => $cartInfo
        ]);
    }

    /**
     * Calculte cart toal
     */
    public function cartTotal(Collection $cart): array
    {
        $subTotal = $cart->map(function($item) {
            return $item['price']*$item['quantity'];
        })->sum();

        $deliveryFee = percentageCalc($subTotal, 5);
        $discount = percentageCalc($subTotal, 2);

        $total = orderTotalCalc($subTotal, $discount, $deliveryFee);
        $tax = taxCalc($total);

        return [
            'subTotal' =>  number_format($subTotal, 2),
            'deliveryFee' => number_format($deliveryFee, 2),
            'discount' => number_format($discount, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2),
        ];
    }

    /**
     * Finally place the order
     */
    protected function placeOrder(Request $request)
    {
        $checkoutInfo = $request->validate([
            'name'              => ['required'],
            'email'             => ['nullable', 'email:rfc,dns'],
            'phone'             => ['required', 'phone'],
            'payment_method'    => ['required', new Enum(PaymentMethods::class)],
        ], [
            'phone.phone' => 'Please enter a valid phone number'
        ]);

        return $this->storeOrder($checkoutInfo);
        
    }

    /**
     * store the order data
     */
    protected function storeOrder($checkoutInfo)
    {
        $cart = session('cart', []);
        $cartTotal = $this->cartTotal(collect($cart));

        if(empty($cart)) {
            return redirect('/')->withErrors(['EmptyCart' => 'Your cart is empty try again with some items']);
        }

        try {
            $order = new Order();
            $order->items = json_encode($cart);
            $order->sub_total = $cartTotal['subTotal'];
            $order->discount = $cartTotal['discount'];
            $order->delivery_fee = $cartTotal['deliveryFee'];
            $order->tax_rate = 2;
            $order->tax_included = 'Yes';
            $order->customer_name = $checkoutInfo['name'];
            $order->customer_email = $checkoutInfo['email'];
            $order->customer_phone = $checkoutInfo['phone'];
            $order->payment_method = $checkoutInfo['payment_method'];
            $order->paid = $checkoutInfo['payment_method'] == PaymentMethods::Online ? 'Yes' : 'No';
            $order->delivery_address = null;
            $order->save();
        } catch (\Throwable $th) {
            return redirect('/checkout')->withErrors(['orderFaild' => 'Your order faild due to internal error']);
        }

        session()->remove('cart');
        return redirect('/')->with('message', ['message' => 'Order created successfully', 'type' => 'success']);
        
        
    }
}
