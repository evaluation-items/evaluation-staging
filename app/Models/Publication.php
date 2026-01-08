<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'imaster.publications';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
