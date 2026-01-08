<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Subdepartment extends Model
{
    use HasFactory;

    protected $table ='imaster.sub_departments';
    protected $guarded = [];
    protected $fillable = ['id','dept_id','name','status','created_at','updated_at'];

  
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
