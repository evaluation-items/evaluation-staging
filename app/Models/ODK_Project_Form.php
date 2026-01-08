<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ODK_Project_Form extends Model{
    protected $table = 'itransaction.odk_project_form';
    protected $primaryKey = 'id';
    protected $fillable = ['id','project_id','name','created_at','target','published_at','updated_at','xml_form_id','state'];
}