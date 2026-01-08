<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Taluka;
use App\Models\District;

class State extends Model
{
    use HasFactory;

    protected $table ='imaster.state';
    protected $primaryKey = 'id';
    protected $guarded = [];
    //protected $primaryKey = 'state_code';


    public function districts()
    {
        return $this->hasMany(District::class, 'state_code', 'state_code')->orderBy('name_e','ASC');
    }

    public function taluka()
    {
        return $this->hasManyThrough(Taluka::class, District::class, 'state_code', 'dcode', 'state_code', 'id');
    }
    // public function taluka()
    // {
    //     return $this->hasManyThrough(Taluka::class,District::class,
    //     'state_code', // Foreign key on the environments table...
    //     'dcode', // Foreign key on the deployments table...
    //     'state_code', // Local key on the projects table...
    //     'state_code' // Local key on the environments table...
    // )->via('district');
    // }
    // public function district(){
    //     return $this->hasMany(District::class,'state_code','state_code');
    // }
    public function dist()
    {
        return $this->hasOne(District::class, 'state_code', 'state_code')->orderBy('name_e','ASC');
    }
    public function scopeActive($query){
        return $query->where('is_active', 1);
    }

}
