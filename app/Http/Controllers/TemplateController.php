<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Template\Template;

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

    public function index()
    {
        $template   = new Template();
        $data       = $template->showtemplate();
        if($data){
            return response()->json(
                [
                    'status'    => true,
                    'message'   => 'List of Company',
                    'data'      => $data
                ],200
            );
        }else{
            return response()->json(
                [
                    'stataus'   => false,
                    'message'   => 'Error Getting List of Company',
                    'data'      => null
                ],400
            );
        }
    }

    public function store(Request $request)
    {
        $name       = $request->input('name');
        $address    = $request->input('address');
        $phone      = $request->input('phone');
        $result     = Company::create(
            [
                'name'      => $name,
                'address'   => $address,
                'phone'     => $phone
            ]
        );

        if($result){
            return response()->json(
                [
                    'status'    => true,
                    'message'   => 'Data Successfully Saved'
                ],201
            );
        }else{
            return response()->json(
                [
                    'status'    => false,
                    'message'   => 'Error Saving Data'
                ]
            );
        }
    }

    public function show($id)
    {
        $company = Company::find($id);
        if($company){
            $company['customers'] = $company->customers;
            return response()->json(
                [
                    'status'    => true,
                    'message'   => 'Company Data',
                    'data'      => $company
                ], 201
            );    
        }else{
            return response()->json(
                [
                    'status'    => false,
                    'message'   => 'Error Getting Data',
                    'data'      => null
                ], 401
            );
        }
    }

    public function update($id, Request $request)
    {
        $company    = Company::find($id);
        if($company){
            $company->name     = $request->input('name');
            $company->address  = $request->input('address');
            $company->phone    = $request->input('phone');
            $result = $company->save();
            if($result){
                return response()->json(
                    [
                        'status'    => true,
                        'message'   => 'Data Successfully Updated'
                    ],201
                );
            }else{
                return response()->json(
                    [
                        'status'    => false,
                        'message'   => 'Error Updating Data'
                    ],401
                );
            }
        }else{
            return response()->json(
                [
                    'status'    => false,
                    'message'   => 'Company id is not found'
                ],401
            );
        }
    }

    public function destroy($id)
    {
        $result = Company::destroy($id);
        if($result){
            return response()->json(
                [
                    'status'    => true,
                    'message'   => 'Successfully Deleting Data'
                ],201
            );
        }else{
            return response()->json(
                [
                    'status'    => false,
                    'message'   => 'Error Deleting Data'
                ],401
            );
        }
    }
}
