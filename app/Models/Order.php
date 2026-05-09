<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
    protected $guarded = [];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function getStatusLabelAttribute() {
        $map = [1=>'Pending',2=>'Processing',3=>'Shipped',4=>'Delivered',5=>'Cancelled'];
        return $map[$this->status] ?? 'Unknown';
    }
    public function getStatusColorAttribute() {
        $map = [1=>'secondary',2=>'primary',3=>'info',4=>'success',5=>'danger'];
        return $map[$this->status] ?? 'dark';
    }
}
