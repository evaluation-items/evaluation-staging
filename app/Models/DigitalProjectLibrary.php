<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalProjectLibrary extends Model
{
    use HasFactory;
    protected $table = 'imaster.digital_project_libraries';
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('status', 1);
    }
}
