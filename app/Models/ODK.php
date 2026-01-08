<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODK extends Model
{
    use HasFactory;
    
    protected $table = 'imaster.odk_study';
    protected $primaryKey = 'osid';
    protected $fillable = ['osid','odk_form_id','study_id','target','created_by','created_at','updated_by','updated_at'];
}
