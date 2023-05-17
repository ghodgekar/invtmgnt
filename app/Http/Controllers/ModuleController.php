<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ModuleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Module::get();
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
                    $evaluators     = new Module();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('modulemasters._id');
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
                        $evaluators         = $evaluators->orderBy('modulemasters._id', 'desc')
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
        $isExists = Module::where('module_code', '=', $request->module_code)->where('module_name', '=', $request->module_name)->first();
        if (!empty($isExists)) {
            return response()->json(['error' => true, 'message' => 'Module Code & Value Must Be Unique'], 400);
        }
        $add = new Module();
        $module_image = '';
        if($request->hasFile('module_image')){
            $originalName = time().$request->file('module_image')->getClientOriginalName();
            $path = $request->file('module_image')->storeAs('/public/images/module/', $originalName);
            $filename = explode("/", $path);
            $module_image = 'storage/app/public/images/module/'.$originalName;
            $add->module_image = $module_image;
        }else{
            $add->module_image = $module_image;
        }
        $add->module_code = $request->module_code;
        $add->module_name = $request->module_name;
        $add->module_slug = Str::slug($request->module_name, '-');
        $add->parent_madule_code = $request->parent_madule_code;
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
        $edit = Module::find($id);
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
        $update = Module::find($request->_id);
        $module_image = '';
        if($request->hasFile('module_image')){
            $originalName = time().$request->file('module_image')->getClientOriginalName();
            $path = $request->file('module_image')->storeAs('/public/images/module/', $originalName);
            $filename = explode("/", $path);
            $module_image = 'storage/app/public/images/module/'.$originalName;
            $update->module_image = $module_image;
        }else{
            $update->module_image = $module_image;
        }
        $update->module_code = $request->module_code;
        $update->module_name = $request->module_name;
        $update->module_slug = Str::slug($request->module_name, '-');
        $update->parent_madule_code = $request->parent_madule_code;
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
        $delete = Module::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }

    public function get_parent_module()
    {
        $lists = Module::where('parent_madule_code','0')->get();
        return response()->json(['data' => $lists]);
    }
}
