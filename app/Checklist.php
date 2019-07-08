<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;
use Carbon\Carbon;

class Checklist extends Model
{
    protected $table = 'checklists';
    // protected $hidden = array('template_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'object_domain',
        'object_id',
        'description',
        'is_completed',
        'completed_at',
        'due',
        'due_interval',
        'due_unit',
        'urgency',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    
    public function setDueAttribute($value)
    {
        $this->attributes['due']    =  Carbon::parse($value);
    }
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at']    =  Carbon::parse($value);
    }
    public function setCompletedAtAttribute($value)
    {
        $this->attributes['completed_at']    =  Carbon::parse($value);
    }
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function template(){
        return $this->belongsTo(Template::class,'template_id');
    }
    public function items(){
        return $this->hasMany(Item::class,'checklist_id');
    }
}
