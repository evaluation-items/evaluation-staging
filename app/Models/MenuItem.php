<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $table = 'imaster.menu_item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name','description','slug','created_at'];

}
