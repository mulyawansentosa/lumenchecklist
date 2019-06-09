<?php

namespace App\Models\Checklist\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ChecklistlinkModel extends Model
{
    protected $table = 'checklistlinks';
    // protected $hidden = array('checklist_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checklist_id','self'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function links(){
        return $this->belongsTo(ChecklistModel::class,'checklist_id');
    }
}
