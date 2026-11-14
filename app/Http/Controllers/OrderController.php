<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class OrderController extends Controller {
 public function index(Request $r) {
  $q=Order::query();
  if($r->filled('status')) { $r->validate(['status'=>Rule::in(['pending','processing','shipped','completed','cancelled'])]); $q->where('status',$r->status); }
  if($r->filled('search')) { $s=substr($r->string('search')->toString(),0,100); $q->where(fn($q)=>$q->where('customer_name','like',"%$s%")->orWhere('customer_email','like',"%$s%")); }
  return $q->latest('id')->paginate(20);
 }
 public function summary() { return ['total'=>Order::count(),'active'=>Order::whereIn('status',['pending','processing','shipped'])->count(),'value'=>Order::where('status','!=','cancelled')->sum('total_cents')]; }
 public function show(Order $order) { return $order; }
 public function store(Request $r) {
  $v=$r->validate(['customer_name'=>'required|string|max:120','customer_email'=>'required|email|max:254','notes'=>'nullable|string|max:2000','items'=>'required|array|min:1|max:50','items.*.name'=>'required|string|max:150','items.*.quantity'=>'required|integer|min:1|max:1000','items.*.unit_price'=>'required|numeric|min:0|max:100000|decimal:0,2']);
  $total=0; $items=[];
  foreach($v['items'] as $i) { $c=(int)round($i['unit_price']*100); $items[]=['name'=>$i['name'],'quantity'=>(int)$i['quantity'],'unit_price_cents'=>$c]; $total+=$c*$i['quantity']; }
  return response()->json(Order::create(['customer_name'=>$v['customer_name'],'customer_email'=>$v['customer_email'],'notes'=>$v['notes']??null,'items'=>$items,'total_cents'=>$total,'status'=>'pending']),201);
 }
 public function update(Request $r, Order $order) { $v=$r->validate(['status'=>['required',Rule::in(['pending','processing','shipped','completed','cancelled'])]]); $order->update($v); return $order; }
 public function destroy(Order $order) { $order->delete(); return response()->noContent(); }
}
