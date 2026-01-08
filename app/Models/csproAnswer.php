<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionOption;

class csproAnswer extends Model
{
    protected $table = 'imaster.cspro_answer';
    protected $primaryKey = 'id';

    public function option()
    {
        return $this->hasOne(QuestionOption::class, 'question_id', 'question_id')
                    ->whereColumn('values', 'answers')
                    ->whereColumn('t_id', 't_id');
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
    
}

