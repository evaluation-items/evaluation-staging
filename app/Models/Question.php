<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionOption;

class Question extends Model
{
    protected $table = 'imaster.questions';
    protected $primaryKey = 'id';

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

}
