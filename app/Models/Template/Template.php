<?php

namespace App\Models\Template;
use App\Models\Template\Eloquent\TemplateModel;

class Template{
    protected $template;

    public function __construct(){
        $this->template = new TemplateModel;
    }

    public function showtemplate(){
        $temp               = $this->template->select('type','id')->find(1);
        $temp['attributes'] = $temp->attributes;       
        $temp['links']      = $temp->links;       
        return $temp;
    }
}