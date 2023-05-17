<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Item::get();
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
                    $evaluators     = new Item();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('itemmasters._id');
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
                        $evaluators         = $evaluators->orderBy('itemmasters._id', 'desc')
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
        $add = new Item();
        $add->brand_code = $request->brand_code;
        $add->category_code = $request->category_code;
        $add->exp_req = $request->exp_req;
        $add->hsn = $request->hsn;
        $add->inventory = $request->inventory;
        $add->item_UOM = $request->item_UOM;
        $add->item_code = $request->item_code;
        $add->item_full_name = $request->item_full_name;
        $add->item_name = $request->item_name;
        $add->item_type = $request->item_type;
        $add->item_weight = $request->item_weight;
        $add->label_reqd = $request->label_reqd;
        $add->manufact_code = $request->manufact_code;
        $add->markup = $request->markup;
        $add->on_mrp = $request->on_mrp;
        $add->qty_in_case = $request->qty_in_case;
        $add->rate_upd = $request->rate_upd;
        $add->shelf_life_dm = $request->shelf_life_dm;
        $add->shelf_life_period = $request->shelf_life_period;
        $add->sub_category_code = $request->sub_category_code;
        $add->tax_code = $request->tax_code;
        $add->markdown = $request->markdown;
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
        $edit = Item::find($id);
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
        $update = Item::find($request->_id);
        $update->brand_code = $request->brand_code;
        $update->category_code = $request->category_code;
        $update->exp_req = $request->exp_req;
        $update->hsn = $request->hsn;
        $update->inventory = $request->inventory;
        $update->item_UOM = $request->item_UOM;
        $update->item_code = $request->item_code;
        $update->item_full_name = $request->item_full_name;
        $update->item_name = $request->item_name;
        $update->item_type = $request->item_type;
        $update->item_weight = $request->item_weight;
        $update->label_reqd = $request->label_reqd;
        $update->manufact_code = $request->manufact_code;
        $update->markup = $request->markup;
        $update->on_mrp = $request->on_mrp;
        $update->qty_in_case = $request->qty_in_case;
        $update->rate_upd = $request->rate_upd;
        $update->shelf_life_dm = $request->shelf_life_dm;
        $update->shelf_life_period = $request->shelf_life_period;
        $update->sub_category_code = $request->sub_category_code;
        $update->tax_code = $request->tax_code;
        $update->markdown = $request->markdown;
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
        $delete = Item::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
