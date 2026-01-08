<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eval_activity_log extends Model{

    use HasFactory;

    protected $table = 'imaster.eval_activity_log';
    protected $primaryKey = 'id';
    protected $fillable = ['id','activity_name','sub_activity_name','created_at'];
}