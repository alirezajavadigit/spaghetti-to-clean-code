<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $guarded = [];
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function getInStockAttribute() { return $this->stock > 0; }
    public function getFormattedPriceAttribute() { return '$'.number_format($this->price,2); }
}
