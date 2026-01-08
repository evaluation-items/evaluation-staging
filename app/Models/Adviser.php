<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adviser extends Model
{
    use HasFactory;
    protected $table = 'imaster.advisers';
    protected $primaryKey = 'adviser_id';
    protected $fillable = ['adviser_id','deptid','imid','adviser_name','adviser_designation','adviser_phone_no','adviser_mobile',
                            'adviser_email','active','created_by','created_at','updated_by','updated_at'];
}