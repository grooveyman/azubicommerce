<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at','id','code', 'cartid'];

    public function cart(){
        return $this->belongsTo(Cart::class, 'cartid');
    }

    public function images(){
        return $this->hasMany(Image::class, 'productid');
    }
}
