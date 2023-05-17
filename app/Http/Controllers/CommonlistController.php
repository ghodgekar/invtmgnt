<?php

namespace App\Http\Controllers;

use App\Commonlist;
use Illuminate\Http\Request;

class CommonlistController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Commonlist::get();
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
                    $evaluators     = new Commonlist();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('commonlistmasters._id');
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
                        $evaluators         = $evaluators->orderBy('commonlistmasters._id', 'desc')
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
        $isExists = Commonlist::where('list_code', '=', $request->list_code)->where('list_value', '=', $request->list_value)->first();
        if (!empty($isExists)) {
            return response()->json(['error' => true, 'message' => 'Common List Code & Value Must Be Unique'], 400);
        }
        $add = new Commonlist();
        $add->list_code = $request->list_code;
        $add->list_value = $request->list_value;
        $add->list_desc = $request->list_desc;
        $add->order_by = $request->order_by;
        $add->loc_code = $request->loc_code;
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
        $edit = Commonlist::find($id);
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
        $update = Commonlist::find($request->_id);
        $update->list_code = $request->list_code;
        $update->list_value = $request->list_value;
        $update->list_desc = $request->list_desc;
        $update->order_by = $request->order_by;
        $update->loc_code = $request->loc_code;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->created_at = now();
        $update->updated_by = $request->updated_by;
        $update->updated_at = now();
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
        $delete = Commonlist::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }


    public function code_list($type)
    {
        $data = Commonlist::where('list_code', $type)->get();
        return response()->json(['data' => $data], 200);
    }
}
