<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coff_id', 'position', 'amount', 'status', 'month', 'date'];
}
