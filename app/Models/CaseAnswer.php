<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cases;

class CaseAnswer extends Model
{
    protected $table = 'imaster.case_answers';

    protected $fillable = ['case_id','record_name','question_label','answer'];

    protected $primaryKey = 'id';

    public function caseItem()
    {
        return $this->belongsTo(Cases::class, 'case_id', 'id');
    }
}
