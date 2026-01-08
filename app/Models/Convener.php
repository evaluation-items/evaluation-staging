<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convener extends Model
{
    use HasFactory;
    protected $table = 'imaster.conveners';
    protected $primaryKey = 'con_id';
    protected $fillable = ['con_id','dept_id','convener_name','convener_designation','convener_phone_no','convener_mobile_no',
                            'convener_email','active','created_by','created_at','updated_by','updated_at'];
}