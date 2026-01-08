<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $table = 'imaster.advertisements';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('active', 1);
    }
}
