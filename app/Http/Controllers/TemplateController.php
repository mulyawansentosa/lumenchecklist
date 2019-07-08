<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Template;
use App\Http\Resources\Template\ListAllChecklistTemplateCollection;

class TemplateController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listallchecklisttemplate()
    {
        try{
            $data      = Template::with('checklist')->paginate();
            if(count($data) > 0){
                return new ListAllChecklistTemplateCollection($data);
            }else{
                return response()->json(
                    [
                        'data'   => []
                    ],200
                );    
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'data'      => [$e->message()]
                ], 500
            );
        }
    }

    public function getchecklisttemplate($templateId)
    {
        try{
            $data      = Template::where('id',$templateId)->with('checklist')->paginate();
            if(count($data) > 0){
                return new ListAllChecklistTemplateCollection($data);
            }else{
                return response()->json(
                    [
                        'data'   => []
                    ],200
                );    
            }
        }catch(\Exception $e){
            return response()->json(
                [
                    'data'      => [$e->message()]
                ], 500
            );
        }
    }
}
