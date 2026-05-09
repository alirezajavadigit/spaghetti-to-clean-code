<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model {
    protected $guarded = [];
    public function orders() { return $this->hasMany(Order::class); }
    public function getTotalSpentAttribute() {
        return \DB::table('orders')->where('customer_id',$this->id)->where('status','!=',5)->sum('total');
    }
}
