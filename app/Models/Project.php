<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    
    protected $table = 'itransaction.projects_form';
    protected $primaryKey = 'id';
    protected $fillable = ['id','osid','version','keyid','projectid','xmlformid','state','name','created_by','created_at','updated_by','updated_at','target','submissions'];
}
