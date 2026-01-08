<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $table = 'imaster.cases';

    protected $fillable = ['case_id','person_id','form_number','title'];

    protected $primaryKey = 'id';


    public function answers()
    {
        return $this->hasMany(CaseAnswer::class, 'case_id', 'id');
    }
}
