<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Village;

class District extends Model
{
    use HasFactory;
    protected $table ='imaster.districts';
    protected $guarded = [];
    protected $primaryKey = 'id';
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'dcode', 'name_e', 'updated_at', 'created_at','state_code','name_g','zone_id','is_border_area','is_international_border',
        'is_tribal','is_developing','is_coastal_area'
    ];

    // public function state()
    // {
    //     return $this->belongsTo(State::class, 'state_code', 'state_code');
    // }

        public function talukas()
        {
            return $this->hasMany(Taluka::class, 'dcode', 'state_code');
        }

        public function state()
        {
            return $this->belongsTo(State::class, 'state_code', 'state_code');
        }
        public function taluka()
        {
            return $this->hasMany(Taluka::class, 'dcode', 'dcode')->orderBy('tname_e','ASC');
        }
       
}
