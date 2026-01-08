<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODK_Project extends Model
{
    
    protected $table = 'imaster.odk_project';
    protected $primaryKey = 'id';
    protected $fillable = ['id','project_id','project_name','created_at',];
}