<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Company::get();
        return response()->json(['lists' => $lists]);
    }

    public function datatable(Request $request){
        try {
            if (count($request->all()) > 0) {
                $total_data                 = 0;
                $data_array                 = [];
                $all_row_data_array         = [];
                $start_limit                = $request['start'];
                $start_end                  = $request['length'];
                $data_query_setting_array   = ['count', 'get_data'];
                foreach ($data_query_setting_array as $data_query_setting_value) {
                    $evaluators     = new Company();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('companymasters._id');
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators = $evaluators->select('*');
                    }
                    if ($data_query_setting_value == 'count') {
                        $evaluators         = $evaluators->get();
                        $total_data         = $evaluators->count();
                        if ($total_data == 0) {
                            break;
                        }
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators         = $evaluators->orderBy('companymasters._id', 'desc')
                            ->skip($start_limit)->take($start_end)->get();
                        $all_row_data_array = $evaluators;
                    }
                }
                $json_data = ['data' => $all_row_data_array, 'draw' => intval($request['draw']), 'recordsFiltered' => $total_data, 'recordsTotal' => $total_data];
                return $this->sendResponse($json_data, 'success');
            } else {
                $json_data = ['data' => [], 'draw' => intval($request['draw']), 'recordsFiltered' => 0, 'recordsTotal' => 0];
                return $this->sendResponse($json_data, 'success');
            }   
            
        } catch (Exception $e) {
            return $this->sendError('Some error occurred', 'error', 422);
            return response()->json(['lists' => $lists]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $add = new Company();
        $company_image = '';
        if($request->hasFile('company_image')){
            $originalName = time().$request->file('company_image')->getClientOriginalName();
            $path = $request->file('company_image')->storeAs('/public/images/company/', $originalName);
            $filename = explode("/", $path);
            $company_image = 'storage/app/public/images/company/'.$originalName;
            $add->company_image = $company_image;
        }else{
            $add->company_image = $company_image;
        }
        $add->comp_code = $request->comp_code;
        $add->comp_name = $request->comp_name;
        $add->type = $request->type;
        $add->addr1 = $request->addr1;
        $add->addr2 = $request->addr2;
        $add->addr3 = $request->addr3;
        $add->city = $request->city;
        $add->state = $request->state;
        $add->country = $request->country;
        $add->std_code = $request->std_code;
        $add->phone = $request->phone;
        $add->mobile = $request->mobile;
        $add->gstin = $request->gstin;
        $add->fassa_no = $request->fassa_no;
        $add->cin_no = $request->cin_no;
        $add->pan_no = $request->pan_no;
        $add->tan_no = $request->tan_no;
        $add->lsttinpin_no = $request->lsttinpin_no;
        $add->cst_no = $request->cst_no;
        $add->coregn_no = $request->coregn_no;
        $add->coregndate = $request->coregndate;
        $add->druglic_no = $request->druglic_no;
        $add->importexport = $request->importexport;
        $add->pincode = $request->pin;
        $add->status = $request->status;
        $add->created_by = $request->created_by;
        $add->updated_by = $request->updated_by;
        $add->save();
        return response()->json(['success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = Company::find($id);
        return response()->json(['data' => array($edit)], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $update = Company::find($request->_id);
        $company_image = '';
        if($request->hasFile('company_image')){
            $originalName = time().$request->file('company_image')->getClientOriginalName();
            $path = $request->file('company_image')->storeAs('/public/images/company/', $originalName);
            $filename = explode("/", $path);
            $company_image = 'storage/app/public/images/company/'.$originalName;
            $update->company_image = $company_image;
        }else{
            $update->company_image = $company_image;
        }
        $update->comp_code = $request->comp_code;
        $update->comp_name = $request->comp_name;
        $update->type = $request->type;
        $update->addr1 = $request->addr1;
        $update->addr2 = $request->addr2;
        $update->addr3 = $request->addr3;
        $update->city = $request->city;
        $update->state = $request->state;
        $update->country = $request->country;
        $update->std_code = $request->std_code;
        $update->phone = $request->phone;
        $update->mobile = $request->mobile;
        $update->gstin = $request->gstin;
        $update->fassa_no = $request->fassa_no;
        $update->cin_no = $request->cin_no;
        $update->pan_no = $request->pan_no;
        $update->tan_no = $request->tan_no;
        $update->lsttinpin_no = $request->lsttinpin_no;
        $update->cst_no = $request->cst_no;
        $update->coregn_no = $request->coregn_no;
        $update->coregndate = $request->coregndate;
        $update->druglic_no = $request->druglic_no;
        $update->importexport = $request->importexport;
        $update->pincode = $request->pin;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->updated_by = $request->updated_by;
        $update->save();
        return response()->json(['success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Company::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
