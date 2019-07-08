<?php
namespace App\Http\Resources\Template;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Template\ListAllChecklistTemplateResource;

class ListAllChecklistTemplateCollection extends ResourceCollection
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
        return [
            'data'  => $this->collection->transform(function ($item){
                // return $item->only(['field','name']);
                return new ListAllChecklistTemplateResource($item);
            })
        ];
    }
    public function withResponse($request, $response)
    {
        // $jsonResponse = json_decode($response->getContent(), true);
        // unset($jsonResponse['links'],$jsonResponse['meta']);
        // $response->setContent(json_encode($jsonResponse));
        $res = json_decode($response->getContent(), true);
        $res['meta']['count']  = $res['meta']['per_page'];
        unset($res['meta']['current_page'],$res['meta']['from'],$res['meta']['last_page'],$res['meta']['path'],$res['meta']['per_page'],$res['meta']['to']);
        $response->setContent(json_encode($res));
    }
}