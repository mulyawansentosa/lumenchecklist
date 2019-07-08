<?php
namespace App\Http\Resources\Template;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Template\TemplateItemCollection;
use App\Http\Resources\Template\TemplateItemResource;
use App\Http\Resources\Template\ChecklistResource;
use App\Item;

class GetChecklistTemplateResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this->checklist->id)){
            $checklist_id   = $this->checklist->id;
        }else{
            $checklist_id   = '';
        }
        return [
                'type'          => 'templates',
                'id'            => $this->id,
                'attributes'    => [
                    'name'      => $this->name,
                    'items'     => new TemplateItemResource(Item::where('checklist_id',$checklist_id)->get()),
                    'checklist' => new ChecklistResource($this->checklist)

                ],
                'links' => [
                    'self' => route('getchecklisttemplate')
                ]
            ];
    }
}