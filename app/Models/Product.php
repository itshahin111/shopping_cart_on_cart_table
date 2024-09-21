<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',           // Product title
        'short_des',       // Short description
        'price',           // Product price
        'discount',        // Discount boolean flag
        'discount_price',  // Discounted price
        'image',
        'star',         // Product image path
        'stock',           // Stock quantity
    ];

    /**
     * Define a relationship to the Cart model.
     * One product can be in many carts.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}