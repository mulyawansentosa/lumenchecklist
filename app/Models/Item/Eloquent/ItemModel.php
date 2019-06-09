<?php

namespace App\Models\Item\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    // protected $hidden = array('checklist_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','checklist_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function checklists(){
        return $this->belongsTo(ChecklistModel::class,'checklist_id');
    }
    public function attributes(){
        return $this->hasOne(ItemattributeModel::class,'item_id');
    }
    public function links(){
        return $this->hasOne(ItemlinkModel::class,'item_id');
    }
}
