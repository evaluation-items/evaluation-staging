<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_user_role_deptid extends Model {
    // use HasFactory;
    protected $table = 'public.user_user_role_deptid';
    protected $fillable = ['id','user_id','user_role_id','dept_id','created_by','created_at','updated_by','updated_at'];
}