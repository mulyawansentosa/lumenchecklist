<?php

namespace App\Models\Checklist\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item\Eloquent\ItemModel;

class ChecklistModel extends Model
{
    protected $table = 'checklists';
    // protected $hidden = array('template_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id','type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function template(){
        return $this->belongsTo(TemplateModel::class,'template_id');
    }
    public function attributes(){
        return $this->hasOne(ChecklistattributeModel::class,'checklist_id');
    }
    public function links(){
        return $this->hasOne(ChecklistlinkModel::class,'checklist_id');
    }
    public function items(){
        return $this->hasMany(ItemModel::class,'checklist_id');
    }
}
