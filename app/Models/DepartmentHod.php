<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class DepartmentHod extends Model
{
    use HasFactory;
    protected $table = 'imaster.department_hod';
    protected $primaryKey = 'id';
    protected $fillable = ['dept_id','name','status','created_at','updated_at'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }
}
