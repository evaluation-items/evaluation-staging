<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialProgress extends Model {
    // use HasFactory;
    protected $table = 'itransaction.financial_progress';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;

}
