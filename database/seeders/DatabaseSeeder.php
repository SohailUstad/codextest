<?php
namespace Database\Seeders;
use App\Models\Order;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder { public function run(): void {
foreach ([['Avery Morgan','avery@example.com','pending','Wireless keyboard',7900,1],['Jordan Lee','jordan@example.com','processing','Desk lamp',4500,2],['Sam Taylor','sam@example.com','shipped','Notebook set',1800,3],['Casey Parker','casey@example.com','completed','USB-C hub',5900,1]] as [$name,$email,$status,$item,$price,$qty]) {
Order::firstOrCreate(['customer_email'=>$email],['customer_name'=>$name,'status'=>$status,'items'=>[['name'=>$item,'quantity'=>$qty,'unit_price_cents'=>$price]],'total_cents'=>$price*$qty,'notes'=>'Sample order']);
}
}}
