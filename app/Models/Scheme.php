<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model {
    // use HasFactory;
    protected $table = 'itransaction.schemes';

    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public $incrementing = false;
    protected $primaryKey = 'scheme_id';

    protected $casts = [
        'major_objective' => 'array',
        'major_indicator' => 'array',
        'major_indicator_hod' => 'array',
        'financial_progress' => 'array'
    ];

    protected $guarded = [
        'id'
    ];

    // protected $fillable = ['scheme_id','draft_id',
    // 'dept_id',
    // 'con_id',
    // 'scheme_name',
    // 'priorit',
    // 'time_duration',
    // 'reference_year',
    // 'major_objective',
    // 'major_indicator',
    // 'im_id',
    // 'nodal_id',
    // 'adviser_id',
    // 'center_ratio',
    // 'state_ratio', 
    // 'scheme_overview',
    // 'scheme_objective',
    // 'sub_scheme',
    // 'commencement_year',
    // 'scheme_status',
    // 'is_sdg',
    // 'scheme_beneficiary_selection_criteria',
    // 'scheme_implementing_procedure',
    // 'major_indicator_hod',
    // 'financial_progress',
    // 'is_evaluation',
    // 'before_eval_name',
    // 'before_eval_year',
    // 'entry_date',
    // 'entered_by',
    // 'updated_on',
    // 'updated_by',
    // 'flag',
    // 'active','fin_year','status','team_id','states'];
}