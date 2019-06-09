<?php

namespace App\Models\Item\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ItemlinkModel extends Model
{
    protected $table = 'itemlinks';
    // protected $hidden = array('item_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id','self'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function links(){
        return $this->belongsTo(ItemModel::class,'item_id');
    }
}
