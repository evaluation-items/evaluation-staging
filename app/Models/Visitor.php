<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table ='imaster.visitors';
    protected $guarded = [];
    protected $primaryKey = 'id';
}