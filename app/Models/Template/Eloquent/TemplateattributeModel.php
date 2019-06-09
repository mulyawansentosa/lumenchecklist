<?php

namespace App\Models\Template\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TemplateattributeModel extends Model
{
    protected $table = 'templateattributes';
    // protected $hidden = array('template_id');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id','name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function template(){
        return $this->belongsTo(TemplateModel::class,'template_id');
    }
}
