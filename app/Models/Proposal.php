<?php
namespace App\Models;
use Carbon\Carbon;
use App\Models\GrFilesList;
use App\Models\NotificationFileList;
use App\Models\BrochureFileList;
use App\Models\PamphletFileList;
use App\Models\CenterStateFiles;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model {
    // use HasFactory;

    protected $table = 'itransaction.proposals';
    protected $primaryKey = 'draft_id';
    public $timestamps = true;
    protected $guarded = [];

   // Model
    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('d-m-Y');
    }

  public function gr_file(){
        return $this->hasMany(GrFilesList::class,'scheme_id','scheme_id');
    }
    public function notification_files(){
        return $this->hasMany(NotificationFileList::class,'scheme_id','scheme_id');
    }
    public function brochure_files(){
        return $this->hasMany(BrochureFileList::class,'scheme_id','scheme_id');
    }
    public function pamphlets_files(){
        return $this->hasMany(PamphletFileList::class,'scheme_id','scheme_id');
    }
    public function otherdetailscenterstate_files(){
        return $this->hasMany(CenterStateFiles::class,'scheme_id','scheme_id');
    }

    
}
