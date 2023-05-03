<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::query()
        ->when(request('search'), function($query, $search) {
            $query->where('name', 'like', '%'.$search.'%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate()
        ->through(function($item) {
            return [
                'id'            => $item->id,
                'name'          => $item->name,
                'name_display'  => $item->name_display,
                'photo'         => $item->photo,
                'status'        => $item->status,
                'price'         => $item->price
            ];
        });
        return Inertia::render('Items', [
            'items' => $items,
            'pageData' => [
                'itemsSearch' => request('search')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('AddItem');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = $request->validate([
            'name'      => ['required'],
            'price'     => ['required', 'numeric', 'min:0.1'],
            'status'    => ['required'],
            'notes'     => ['required'],
            'name_display'  => ['nullable'],
            'sku'           => ['nullable'],
            'photo'         => ['nullable']
        ]);

        $item = Item::insertGetId([...$form, 'photo' => null]);

        $item = Item::findOrFail($item);

        if($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = rand().'.'.$file->extension();
            $file->move(public_path('uploads/'), $file_name);

            $item->photo =  $file_name;
        }

        $item->restaurant_id = auth()->user()?->id;
        $item->created_at = Carbon::now();
        $item->updated_at = Carbon::now();
        $item->save();

        return redirect(route('items.index'))->with('message', ['message' => 'New Item Added Successfully', 'type' => 'success']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('EditItem', [
            'item' => Item::select('name', 'name_display', 'price', 'sku', 'notes', 'status', 'photo as old_photo', 'id')->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $form = $request->validate([
            'name'      => ['required'],
            'price'     => ['required', 'numeric', 'min:0.1'],
            'status'    => ['required'],
            'notes'     => ['required'],
            'name_display'  => ['nullable'],
            'sku'           => ['nullable'],
            'photo'         => ['nullable']
        ]);

        $item = Item::findOrFail($id);

        $item->name = $request->name;
        $item->price = $request->price;
        $item->status = $request->status;
        $item->notes = $request->notes;
        $item->name_display = $request->name_display;
        $item->sku = $request->sku;

        if($request->hasFile('photo')) {
            if($item->photo && File::exists(public_path('uploads/'.$item->photo))) {
                File::delete(public_path('uploads/'.$item->photo));
            }

            $file = $request->file('photo');
            $file_name = rand().'.'.$file->extension();
            $file->move(public_path('uploads/'), $file_name);

            $item->photo =  $file_name;
        }

        $item->update();
        return redirect(route('items.index'))->with('message', ['message' => 'Item Updated Successfully', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::with('menu_items')->findOrFail($id);

        if($item->photo && File::exists(public_path('uploads/'.$item->photo))) {
            File::delete(public_path('uploads/'.$item->photo));
        }

        $item->menu_items()->delete();
        $item->delete();

        return redirect(route('items.index'))->with('message', ['message' => 'Item Deleted Successfully', 'type' => 'success']);
    }

    /* 
    * change item status
    */
    public function change_status($id)
    {
        $status = request('status') ?? 3;

        $item = Item::findOrFail($id);
        $item->status = $status;
        if(!$item->update()) {
            return redirect(route('items.index'))->with('message', ['message' => 'Faild to change status', 'type' => 'warning']);
        }
    }
}
