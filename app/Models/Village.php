<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Taluka;

class Village extends Model
{
    use HasFactory;
    protected $table ='imaster.village';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function district(){
        return $this->hasMany(District::class,'dcode','dcode');
    }
    public function taluka_list()
    {
        return $this->hasMany(Taluka::class, 'tcode', 'tcode');
    }

}
