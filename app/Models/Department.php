<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;
use App\Models\Subdepartment;

class Department extends Model
{
    use HasFactory;
    protected $table = 'imaster.departments';
    protected $primaryKey = 'dept_id';
    protected $fillable = ['dept_name','dept_address','status','created_at','updated_at'];

    public function stages()
    {
        return $this->hasMany(Stage::class, 'dept_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function sub_department()
    {
        return $this->hasMany(Subdepartment::class, 'dept_id');
    }
}