<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'coff_id','tea_type', 
        'description', 'address', 'promo_code',
        'pay_type','pay_confirmation','status'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'coff_id');
    }
}
