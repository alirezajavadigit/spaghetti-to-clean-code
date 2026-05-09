<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller {
    public function index() {
        if (!Auth::check()) return redirect('/login');
        $q=request('q');
        if ($q) { $products=DB::select("SELECT * FROM products WHERE name LIKE '%$q%' OR sku LIKE '%$q%'"); }
        else { $products=Product::paginate(20); }
        return view('products.index',compact('products','q'));
    }
    public function create() { if (!Auth::check()) return redirect('/login'); return view('products.create'); }
    public function store(Request $request) {
        if (!Auth::check()) return redirect('/login');
        if (!$request->name||!$request->price||!$request->sku) return back()->with('error','Name, SKU and price are required.')->withInput();
        $imagePath=null;
        if ($request->hasFile('image')) { $file=$request->file('image'); $fn=time().'_'.$file->getClientOriginalName(); $file->move(public_path('uploads/products'),$fn); $imagePath=$fn; }
        $p=new Product();
        $p->sku=$request->sku; $p->name=$request->name; $p->description=$request->description;
        $p->price=$request->price; $p->stock=$request->stock??0; $p->category=$request->category;
        $p->image=$imagePath; $p->active=$request->has('active')?1:0; $p->save();
        return redirect('/products')->with('success','Product created.');
    }
    public function edit($id) { if (!Auth::check()) return redirect('/login'); $product=Product::find($id); return view('products.edit',compact('product')); }
    public function update(Request $request,$id) {
        if (!Auth::check()) return redirect('/login');
        $product=Product::find($id);
        if (!$request->name||!$request->price) return back()->with('error','Name and price are required.');
        if ($request->hasFile('image')) { $file=$request->file('image'); $fn=time().'_'.$file->getClientOriginalName(); $file->move(public_path('uploads/products'),$fn); $product->image=$fn; }
        $product->name=$request->name; $product->description=$request->description;
        $product->price=$request->price; $product->stock=$request->stock;
        $product->category=$request->category; $product->active=$request->has('active')?1:0;
        $product->save(); return redirect('/products')->with('success','Product updated.');
    }
    public function destroy($id) {
        if (!Auth::check()) return redirect('/login');
        if (Auth::user()->role!='admin') return redirect('/products')->with('error','Not authorized.');
        $inOrders=DB::select('SELECT id FROM order_items WHERE product_id='.$id.' LIMIT 1');
        if (!empty($inOrders)) return redirect('/products')->with('error','Cannot delete a product that has orders.');
        Product::find($id)->delete(); return redirect('/products')->with('success','Product deleted.');
    }
}
