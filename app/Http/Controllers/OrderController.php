<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order; use App\Models\OrderItem; use App\Models\Customer; use App\Models\Product;
class OrderController extends Controller {
    public function index() {
        if (!Auth::check()) return redirect('/login');
        $orders=Order::orderBy('created_at','desc')->paginate(20);
        return view('orders.index',compact('orders'));
    }
    public function create() {
        if (!Auth::check()) return redirect('/login');
        $customers=Customer::all();
        $products=DB::select('SELECT * FROM products WHERE active=1 AND stock>0');
        return view('orders.create',compact('customers','products'));
    }
    public function store(Request $request) {
        if (!Auth::check()) return redirect('/login');
        if (!$request->customer_id) return back()->with('error','Please select a customer.');
        if (empty($request->products)) return back()->with('error','Please select at least one product.');
        if (!$request->shipping_address) return back()->with('error','Shipping address is required.');
        $existingPending=DB::select("SELECT id FROM orders WHERE customer_id=".$request->customer_id." AND status=1");
        if (!empty($existingPending)) return back()->with('error','This customer already has a pending order.');
        $orderNum='ORD-'.rand(1000,9999);
        $subtotal=0; $lineItems=[];
        foreach ($request->products as $productId=>$qty) {
            $qty=(int)$qty; if ($qty<=0) continue;
            $product=Product::find($productId); if (!$product) continue;
            if ($product->stock<$qty) return back()->with('error','Insufficient stock for: '.$product->name);
            $product->stock-=$qty; $product->save();
            $subtotal+=(float)$product->price*$qty;
            $lineItems[]=['product_id'=>$product->id,'qty'=>$qty,'price'=>$product->price];
        }
        if (empty($lineItems)) return back()->with('error','No valid products selected.');
        $tax=$subtotal*0.085; $total=$subtotal+$tax;
        $order=new Order();
        $order->order_number=$orderNum; $order->customer_id=$request->customer_id;
        $order->user_id=Auth::id(); $order->status=1;
        $order->subtotal=$subtotal; $order->tax=round($tax,2); $order->total=round($total,2);
        $order->notes=$request->notes; $order->shipping_address=$request->shipping_address;
        $order->due_date=$request->due_date?:null; $order->save();
        foreach ($lineItems as $li) {
            $item=new OrderItem();
            $item->order_id=$order->id; $item->product_id=$li['product_id'];
            $item->quantity=$li['qty']; $item->price=$li['price']; $item->save();
        }
        if ($request->hasFile('attachment')) {
            $file=$request->file('attachment'); $fn=$file->getClientOriginalName();
            $file->move(public_path('uploads'),$fn); $order->attachment=$fn; $order->save();
        }
        return redirect('/orders')->with('success','Order '.$orderNum.' created.');
    }
    public function show($id) {
        if (!Auth::check()) return redirect('/login');
        $order=Order::find($id);
        if (!$order) return redirect('/orders')->with('error','Order not found.');
        $customer=Customer::find($order->customer_id);
        $cb=DB::select('SELECT name FROM users WHERE id='.$order->user_id);
        $createdBy=$cb[0]->name??'Unknown';
        $items=OrderItem::where('order_id',$id)->get();
        $products=[];
        foreach ($items as $item) { $products[$item->id]=Product::find($item->product_id); }
        return view('orders.show',compact('order','customer','items','products','createdBy'));
    }
    public function edit($id) {
        if (!Auth::check()) return redirect('/login');
        $order=Order::find($id);
        if ($order->status==4||$order->status==5) return redirect('/orders/'.$id)->with('error','Cannot edit a completed or cancelled order.');
        $customers=Customer::all();
        return view('orders.edit',compact('order','customers'));
    }
    public function update(Request $request,$id) {
        if (!Auth::check()) return redirect('/login');
        $order=Order::find($id);
        $order->customer_id=$request->customer_id; $order->notes=$request->notes;
        $order->shipping_address=$request->shipping_address; $order->due_date=$request->due_date;
        $order->save(); return redirect('/orders/'.$id)->with('success','Order updated.');
    }
    public function destroy($id) {
        if (!Auth::check()) return redirect('/login');
        if (Auth::user()->role!='admin') return redirect('/orders')->with('error','Only admins can delete orders.');
        $order=Order::find($id);
        if (!$order) return redirect('/orders');
        DB::table('order_items')->where('order_id',$id)->delete(); $order->delete();
        return redirect('/orders')->with('success','Order deleted.');
    }
    public function search(Request $request) {
        if (!Auth::check()) return redirect('/login');
        $q=$request->get('q',''); $status=$request->get('status','');
        $statusFilter=$status!==''?"AND orders.status=$status":'';
        $orders=DB::select("SELECT orders.*,customers.name as customer_name FROM orders LEFT JOIN customers ON orders.customer_id=customers.id WHERE (customers.name LIKE '%$q%' OR orders.order_number LIKE '%$q%') $statusFilter ORDER BY orders.created_at DESC");
        $isSearch=true;
        return view('orders.index',compact('orders','isSearch','q','status'));
    }
    public function updateStatus(Request $request,$id) {
        if (!Auth::check()) return redirect('/login');
        $order=Order::find($id);
        if (!$request->status) return back()->with('error','Status is required.');
        $order->status=$request->status; $order->save();
        return redirect('/orders/'.$id)->with('success','Status updated.');
    }
}
