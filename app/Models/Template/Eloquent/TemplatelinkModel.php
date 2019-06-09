<?php

namespace App\Models\Template\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TemplatelinkModel extends Model
{
    protected $table = 'templatelinks';
    // protected $hidden = array('template_id','created_at','updated_at');    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id','self'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function links(){
        return $this->belongsTo(TemplateModel::class,'template_id');
    }
}
