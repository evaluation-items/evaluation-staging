<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdkUser extends Model
{
    use HasFactory;

    protected $table = 'imaster.odk_users';
    protected $primaryKey = 'id';

    protected $fillable = ["email","password","status","encrypt_password"];
}
