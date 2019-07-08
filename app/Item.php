<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Item extends Model
{
    protected $table = 'items';
    // protected $hidden = array('checklist_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checklist_id',
        'user_id',
        'description',
        'is_completed',
        'completed_at',
        'updated_by',
        'due',
        'urgency',
        'assignee_id',
        'task_id'
    ];
    public function getIsCompletedAttribute($is_completed){
        return $is_completed == 1 ? true : false;
    }
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
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function checklists(){
        return $this->belongsTo(Checklist::class,'checklist_id');
    }
}
