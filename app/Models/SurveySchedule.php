<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SurveySchedule extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'itransaction.survey_schedules';

    protected $fillable = ['id','user_id','scheme_id','dept_id','draft_id','status','scheme_name','total_scheme'];

}
