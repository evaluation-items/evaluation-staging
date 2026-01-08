<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nodal extends Model
{
    use HasFactory;
    protected $table = 'imaster.nodals';
    protected $primaryKey = 'nodalid';
    protected $fillable = ['nodalid','deptid','imid','nodal_name','designation','phone_no','mobile','email',
    // 'address','dcode','pincode',
    'active','enterd_by','updated_by','created_at','updated_at'];
}