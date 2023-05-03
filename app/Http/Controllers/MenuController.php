<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::query()
        ->when(request('search'), function($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate()
        ->through(function($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'name_display' => $menu->name_display,
                'status'        => $menu->status,
                'updated_at'    => Carbon::parse($menu->updated_at)->diffForHumans()
            ];
        });
        return Inertia::render('Menus', [
            'menus' => $menus,
            'pageData' => [
                'menuSearch' => request('search'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('AddMenu');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'name_display' => ['nullable'],
            'status'    => ['required', 'numeric', 'max:2']
        ]);

        $menu = new Menu();

        $menu->name = $request->name;
        $menu->name_display = $request->name_display;
        $menu->status = $request->status;
        $menu->restaurant_id = auth()->user()?->id;
        $menu->save();

        return redirect(route('menus.index'))->with('message', ['message' => 'Menu Added Successfully', 'type' => 'success']);
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
        return Inertia::render('EditMenu', [
            'menu' => Menu::select('id', 'name', 'name_display', 'status')->findOrFail($id),
            'items' => Menu::with('items')->find($id)->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item->id,
                    'name' => $item->item->name,
                    'name_display' => $item->item->name_display,
                    'price' => $item->item->price,
                    'photo' => $item->item->photo,
                    'price' => $item->item->sku,
                    'status' => $item->item->status,
                    'notes' => $item->item->notes,
                ];
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $menu->update($request->all());

        return redirect(route('menus.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }

    /* 
    * change menu status
    */
    public function change_status($id)
    {
        $status = request('status') ?? 2;
        $menu = Menu::findOrFail($id);
        $menu->status = $status;
        if(!$menu->update()) {
            return redirect(route('menus.index'))->with('message', ['message' => 'Faild to change status', 'type' => 'warning']);
        }
    }
}
