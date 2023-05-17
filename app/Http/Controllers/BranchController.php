<?php

namespace App\Http\Controllers;

use App\Branch;
use App\State;
use Illuminate\Http\Request;

class BranchController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Branch::get();
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
                    $evaluators     = new Branch();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('branchmasters._id');
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators = $evaluators->select('*')->leftJoin("companymasters", "companymasters.comp_code", "=", "branchmasters.comp_code");
                    }
                    if ($data_query_setting_value == 'count') {
                        // $evaluators         = $evaluators->get();
                        $total_data         = $evaluators->count();
                        if ($total_data == 0) {
                            break;
                        }
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators         = $evaluators->orderBy('branchmasters._id', 'desc')
                            ->offset($start_limit)->limit($start_end)->get();
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
        $isExists = Module::where('loc_code', '=', $request->loc_code)->where('loc_no', '=', $request->loc_no)->first();
        if (!empty($isExists)) {
            return response()->json(['error' => true, 'message' => 'Branch Code & Value Must Be Unique'], 400);
        }
        $add = new Branch();
        $image = '';
        if($request->hasFile('image')){
            $originalName = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('/public/images/branch/', $originalName);
            $filename = explode("/", $path);
            $image = 'storage/app/public/images/branch/'.$originalName;
            $add->image = $image;
        }else{
            $add->image = $image;
        }
        $add->loc_code = $request->loc_code;
        $add->loc_no = $request->loc_no;
        $add->loc_name = $request->loc_name;
        $add->comp_code = $request->comp_code;
        $add->addr1 = $request->addr1;
        $add->addr2 = $request->addr2;
        $add->city = $request->city;
        $add->state = $request->state;
        $add->country = $request->country;
        $add->pin = $request->pin;
        $add->pincode = $request->pin;
        $add->phone = $request->phone;
        $add->gstin = $request->gstin;
        $add->bank_name = $request->bank_name;
        $add->bank_ac_no = $request->bank_ac_no;
        $add->status = $request->status;
        $add->created_by = $request->created_by;
        $add->updated_by = $request->updated_by;
        $add->save();
        return response()->json(['success' => true, 'message' => 'Data Saved Successfully'], 200);
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
        $edit = Branch::find($id);
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
        $update = Branch::find($request->_id);
        $image = '';
        if($request->hasFile('image')){
            $originalName = time().$request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('/public/images/branch/', $originalName);
            $filename = explode("/", $path);
            $image = 'storage/app/public/images/branch/'.$originalName;
            $update->image = $image;
        }else{
            $update->image = $image;
        }
        $update->loc_code = $request->loc_code;
        $update->loc_no = $request->loc_no;
        $update->loc_name = $request->loc_name;
        $update->comp_code = $request->comp_code;
        $update->addr1 = $request->addr1;
        $update->addr2 = $request->addr2;
        $update->city = $request->city;
        $update->state = $request->state;
        $update->country = $request->country;
        $update->pin = $request->pin;
        $update->pincode = $request->pin;
        $update->phone = $request->phone;
        $update->gstin = $request->gstin;
        $update->bank_name = $request->bank_name;
        $update->bank_ac_no = $request->bank_ac_no;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->updated_by = $request->updated_by;
        $update->save();
        return response()->json(['success' => true, 'message' => 'Data Updated Successfully'], 200);
    }

    public function getState(Request $request, $state)
    {
        $data = Branch::where('loc_code', $state)->first();
        $state = State::where('state_name', $data->state)->first();
        $data->{'stateData'} = $state;
        return response()->json(['data' => array($data)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Branch::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }

}
