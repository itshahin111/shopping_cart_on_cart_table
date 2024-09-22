<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // ID of the user
        'product_id',     // ID of the product in the cart
        'qty',            // Quantity of the product in the cart
        'price',          // Total price for the given quantity
    ];

    /**
     * Define a relationship to the User model.
     * A cart belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define a relationship to the Product model.
     * A cart belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
