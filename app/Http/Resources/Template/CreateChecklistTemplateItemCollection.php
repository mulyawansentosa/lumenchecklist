<?php
namespace App\Http\Resources\Template;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Template\GetChecklistTemplateResource;

class CreateChecklistTemplateItemCollection extends ResourceCollection
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
        // return parent::toArray($request);
        return $this->collection->transform(function ($item){
                // return $item->only(['field','name']);
                return new CreateChecklistTemplateItemResource($item);
            });
    }
}