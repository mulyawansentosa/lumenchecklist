<?php

namespace App\Models\Item\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ItemattributeModel extends Model
{
    protected $table = 'itemattributes';
    // protected $hidden = array('item_id');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'description',
        'is_completed',
        'completed_at',
        'updated_by',
        'due',
        'urgency',
        'assignee_id',
        'task_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function item(){
        return $this->belongsTo(ItemModel::class,'item_id');
    }
}
