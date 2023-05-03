<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id', 'restaurant_id'];

    public function menu_items() {
        return $this->hasMany(MenuItem::class);
    }
}
