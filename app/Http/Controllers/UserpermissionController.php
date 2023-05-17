<?php

namespace App\Http\Controllers;

use App\Userpermission;
use Illuminate\Http\Request;

class UserpermissionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Userpermission::get();
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
                    $evaluators     = new Userpermission();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('userpermissionmasters._id');
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
                        $evaluators         = $evaluators->orderBy('userpermissionmasters._id', 'desc')
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
        $add = new Userpermission();
        $add->user_code = $request->user_code;
        $add->module_code = $request->module_code;
        $add->is_open = $request->is_open;
        $add->is_entry = $request->is_entry;
        $add->is_modify = $request->is_modify;
        $add->is_auth = $request->is_auth;
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
        $edit = Userpermission::find($id);
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
        $update = Userpermission::find($request->_id);
        $update->user_code = $request->user_code;
        $update->module_code = $request->module_code;
        $update->is_open = $request->is_open;
        $update->is_entry = $request->is_entry;
        $update->is_modify = $request->is_modify;
        $update->is_auth = $request->is_auth;
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
        $delete = Userpermission::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
