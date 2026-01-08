<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsproData extends Model
{
    use HasFactory;

    protected $table = 'imaster.cspro_data';

    protected $fillable = ['record_id', 'name', 'age', 'gender', 'location'];
}
