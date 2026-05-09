<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer; use App\Models\Order;
use Illuminate\Support\Facades\DB;
class CustomerController extends Controller {
    public function index() { if (!Auth::check()) return redirect('/login'); $customers=Customer::paginate(20); return view('customers.index',compact('customers')); }
    public function show($id) {
        if (!Auth::check()) return redirect('/login');
        $customer=Customer::find($id); $orders=Order::where('customer_id',$id)->get();
        $totalSpent=0; foreach ($orders as $o) { if ($o->status!=5) $totalSpent+=$o->total; }
        return view('customers.show',compact('customer','orders','totalSpent'));
    }
    public function create() { if (!Auth::check()) return redirect('/login'); return view('customers.create'); }
    public function store(Request $request) {
        if (!Auth::check()) return redirect('/login');
        if (!$request->name||!$request->email) return back()->with('error','Name and email are required.');
        if (Customer::where('email',$request->email)->first()) return back()->with('error','A customer with this email already exists.');
        $c=new Customer();
        $c->name=$request->name; $c->email=$request->email; $c->phone=$request->phone;
        $c->address=$request->address; $c->company=$request->company; $c->credit_limit=$request->credit_limit??0;
        $c->save(); return redirect('/customers')->with('success','Customer created.');
    }
    public function edit($id) { if (!Auth::check()) return redirect('/login'); $customer=Customer::find($id); return view('customers.edit',compact('customer')); }
    public function update(Request $request,$id) {
        if (!Auth::check()) return redirect('/login');
        $customer=Customer::find($id);
        $customer->name=$request->name; $customer->email=$request->email; $customer->phone=$request->phone;
        $customer->address=$request->address; $customer->company=$request->company; $customer->credit_limit=$request->credit_limit;
        $customer->save(); return redirect('/customers')->with('success','Customer updated.');
    }
    public function destroy($id) {
        if (!Auth::check()) return redirect('/login');
        if (Auth::user()->role!='admin') return redirect('/customers')->with('error','Not authorized.');
        $c=Customer::find($id);
        if (Order::where('customer_id',$id)->count()) return redirect('/customers')->with('error','Cannot delete customer with existing orders.');
        $c->delete(); return redirect('/customers')->with('success','Customer deleted.');
    }
}
