<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NodalDesignation extends Model
{
    use HasFactory;

    protected $table ='imaster.nodal_designations';
    protected $guarded = [];
    protected $primaryKey = 'id';
}
