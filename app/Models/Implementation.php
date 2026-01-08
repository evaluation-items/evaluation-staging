<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Implementation extends Model
{
    use HasFactory;
    protected $table = 'imaster.implementations';
    protected $fillable = ['id','deptid','name','address','active','enterd_by','updated_by','created_at','updated_at'];
    // 'id','email','phone_no','dcode','pincode'
}