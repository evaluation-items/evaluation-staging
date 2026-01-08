<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table ='imaster.units_of_physical';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
