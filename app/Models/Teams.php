<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model {

    protected $table = 'itransaction.team_members';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
    protected $primaryKey = 'Teams_id';
    protected $guarded = [];

}
