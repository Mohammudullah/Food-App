<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_display', 'status'];

    //get all items linked to this menu
    public function items() {
        return $this->belongsToMany(Item::class, 'menu_items');
    }
}
