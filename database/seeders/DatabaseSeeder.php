<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert(['name'=>'Admin User','email'=>'admin@example.com','password'=>md5('password123'),'role'=>'admin','active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        DB::table('users')->insert(['name'=>'Staff Member','email'=>'staff@example.com','password'=>md5('password123'),'role'=>'staff','active'=>1,'created_at'=>now(),'updated_at'=>now()]);
        $customers = [
            ['name'=>'Acme Corp','email'=>'billing@acme.com','phone'=>'555-0100','address'=>'123 Main St','company'=>'Acme','credit_limit'=>10000],
            ['name'=>'Globex Inc','email'=>'ap@globex.com','phone'=>'555-0101','address'=>'456 Oak Ave','company'=>'Globex','credit_limit'=>5000],
            ['name'=>'John Smith','email'=>'john@smith.com','phone'=>'555-0102','address'=>'789 Pine Rd','company'=>null,'credit_limit'=>2000],
            ['name'=>'Initech','email'=>'orders@initech.com','phone'=>'555-0103','address'=>'321 Elm St','company'=>'Initech','credit_limit'=>15000],
            ['name'=>'Umbrella Ltd','email'=>'purchasing@umbrella.com','phone'=>'555-0104','address'=>'654 Maple Dr','company'=>'Umbrella','credit_limit'=>20000],
        ];
        foreach ($customers as $c) { DB::table('customers')->insert(array_merge($c,['created_at'=>now(),'updated_at'=>now()])); }
        $products = [
            ['sku'=>'WIDGET-001','name'=>'Basic Widget','price'=>'9.99','stock'=>150,'category'=>'Widgets','active'=>1],
            ['sku'=>'WIDGET-002','name'=>'Premium Widget','price'=>'24.99','stock'=>75,'category'=>'Widgets','active'=>1],
            ['sku'=>'GADGET-001','name'=>'Standard Gadget','price'=>'49.99','stock'=>40,'category'=>'Gadgets','active'=>1],
            ['sku'=>'GADGET-002','name'=>'Pro Gadget','price'=>'99.99','stock'=>20,'category'=>'Gadgets','active'=>1],
            ['sku'=>'PART-001','name'=>'Replacement Part A','price'=>'4.99','stock'=>500,'category'=>'Parts','active'=>1],
            ['sku'=>'PART-002','name'=>'Replacement Part B','price'=>'7.49','stock'=>300,'category'=>'Parts','active'=>1],
            ['sku'=>'TOOL-001','name'=>'Assembly Tool','price'=>'34.99','stock'=>0,'category'=>'Tools','active'=>0],
        ];
        foreach ($products as $p) { DB::table('products')->insert(array_merge($p,['created_at'=>now(),'updated_at'=>now()])); }
        $orders = [
            ['order_number'=>'ORD-1001','customer_id'=>1,'user_id'=>1,'status'=>4,'subtotal'=>124.95,'tax'=>10.62,'total'=>135.57,'shipping_address'=>'123 Main St','created_at'=>now()->subDays(30)],
            ['order_number'=>'ORD-1002','customer_id'=>2,'user_id'=>1,'status'=>3,'subtotal'=>299.94,'tax'=>25.49,'total'=>325.43,'shipping_address'=>'456 Oak Ave','created_at'=>now()->subDays(15)],
            ['order_number'=>'ORD-1003','customer_id'=>3,'user_id'=>2,'status'=>2,'subtotal'=>49.99,'tax'=>4.25,'total'=>54.24,'shipping_address'=>'789 Pine Rd','created_at'=>now()->subDays(5)],
            ['order_number'=>'ORD-1004','customer_id'=>1,'user_id'=>2,'status'=>1,'subtotal'=>74.97,'tax'=>6.37,'total'=>81.34,'shipping_address'=>'123 Main St','created_at'=>now()->subDays(2)],
            ['order_number'=>'ORD-1005','customer_id'=>4,'user_id'=>1,'status'=>5,'subtotal'=>199.96,'tax'=>16.99,'total'=>216.95,'shipping_address'=>'321 Elm St','created_at'=>now()->subDays(20)],
        ];
        foreach ($orders as $o) { DB::table('orders')->insert(array_merge($o,['updated_at'=>now()])); }
        DB::table('order_items')->insert([
            ['order_id'=>1,'product_id'=>1,'quantity'=>5,'price'=>'9.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>1,'product_id'=>3,'quantity'=>1,'price'=>'49.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>1,'product_id'=>5,'quantity'=>10,'price'=>'4.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>2,'product_id'=>4,'quantity'=>3,'price'=>'99.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>3,'product_id'=>3,'quantity'=>1,'price'=>'49.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>4,'product_id'=>1,'quantity'=>3,'price'=>'9.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>4,'product_id'=>6,'quantity'=>6,'price'=>'7.49','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>5,'product_id'=>2,'quantity'=>4,'price'=>'24.99','created_at'=>now(),'updated_at'=>now()],
            ['order_id'=>5,'product_id'=>5,'quantity'=>20,'price'=>'4.99','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
