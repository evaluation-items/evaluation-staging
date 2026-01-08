<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiaries extends Model
{
    use HasFactory;
    protected $table ='imaster.beneficiaries_item';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
