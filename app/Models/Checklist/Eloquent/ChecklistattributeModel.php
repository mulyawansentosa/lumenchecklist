<?php

namespace App\Models\Checklist\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ChecklistattributeModel extends Model
{
    protected $table = 'checklistattributes';
    // protected $hidden = array('checklist_id');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checklist_id',
        'object_domain',
        'object_id',
        'description',
        'is_completed',
        'completed_at',
        'updated_by',
        'due',
        'urgency'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function checklist(){
        return $this->belongsTo(ChecklistModel::class,'checklist_id');
    }
}
