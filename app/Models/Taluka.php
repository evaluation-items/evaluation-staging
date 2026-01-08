<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\State;

class Taluka extends Model {
    protected $table ='imaster.taluka';
    protected $guarded = [];
   protected $primaryKey = 'tid';


    public function district()
    {
        return $this->HasOne(District::class, 'dcode', 'dcode');
    }

    
}

