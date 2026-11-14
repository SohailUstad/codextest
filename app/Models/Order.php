<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
 protected $fillable = ['customer_name','customer_email','status','items','total_cents','notes'];
 protected function casts(): array { return ['items'=>'array','total_cents'=>'integer']; }
}
