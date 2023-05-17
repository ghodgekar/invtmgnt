<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Str;

class ParameterController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Parameter::orderBy('param_code', 'asc')->get();
        return response()->json(['lists' => $lists]);
    }

    public function datatable(Request $request){
        try {
            // if (count($request->all()) > 0) {
                $total_data                 = 0;
                $data_array                 = [];
                $all_row_data_array         = [];
                $start_limit                = $request['start'];
                $start_end                  = $request['length'];
                $data_query_setting_array   = ['count', 'get_data'];
                foreach ($data_query_setting_array as $data_query_setting_value) {
                    $evaluators     = new Parameter();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('_id');
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators = $evaluators->select('*')->where('status', 'Active');
                    }
                    if ($data_query_setting_value == 'count') {
                        $evaluators         = $evaluators->get();
                        $total_data         = $evaluators->count();
                        if ($total_data == 0) {
                            break;
                        }
                    }
                    if ($data_query_setting_value == 'get_data') {
                        $evaluators         = $evaluators->orderBy('_id', 'desc')
                            ->skip($start_limit)->take($start_end)->get();
                        $all_row_data_array = $evaluators;
                    }
                }
                $json_data = ['data' => $all_row_data_array, 'draw' => intval($request['draw']), 'recordsFiltered' => intval($total_data), 'recordsTotal' => intval($total_data)];
                return $this->sendResponse($json_data, 'success');
            // } else {
            //     $json_data = ['data' => [], 'draw' => intval($request['draw']), 'recordsFiltered' => 0, 'recordsTotal' => 0];
            //     return $this->sendResponse($json_data, 'success');
            // }
        } catch (Exception $e) {
            return $this->sendError('Some error occurred', 'error', 422);
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
        $isExists = Parameter::where('param_code', '=', $request->param_code)->first();
        if (!empty($isExists)) {
            return response()->json(['error' => true, 'message' => 'Parameter Code Must Be Unique'], 400);
        }
        $add = new Parameter();
        $add->param_code = strtoupper($request->param_code);
        $add->param_value = $request->param_value;
        $add->param_desc = $request->param_desc;
        $add->data_type = $request->data_type;
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
        $edit = Parameter::find($id);
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
        $update = Parameter::find($request->_id);
        $update->param_code = strtoupper($request->param_code);
        $update->param_value = $request->param_value;
        $update->param_desc = $request->param_desc;
        $update->data_type = $request->data_type;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->updated_by = $request->updated_by;
        $update->save();
        return response()->json(['success' => true, 'message' => 'Data Updated Successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Parameter::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }

    public function code_list($type)
    {
        $data = Parameter::where('param_code', $type)->get();
        return response()->json(['data' => $data], 200);
    }
}
