<?php

namespace App\Models\Template\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TemplateModel extends Model
{
    protected $table = 'templates';
    // protected $hidden = array('created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function attributes(){
        return $this->hasOne(TemplateattributeModel::class,'template_id');
    }
    public function links(){
        return $this->hasOne(TemplatelinkModel::class,'template_id');
    }
    public function checklist(){
        return $this->hasOne(ChecklistModel::class,'template_id');
    }
}
