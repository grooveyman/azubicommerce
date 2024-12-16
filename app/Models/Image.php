<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'id', 'code', 'productid'];
    
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
