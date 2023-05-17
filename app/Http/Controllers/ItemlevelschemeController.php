<?php

namespace App\Http\Controllers;

use App\Itemlevelscheme;
use Illuminate\Http\Request;

class ItemlevelschemeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Itemlevelscheme::get();
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
                    $evaluators     = new Itemlevelscheme();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('itemlevelschememasters._id');
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
                        $evaluators         = $evaluators->orderBy('itemlevelschememasters._id', 'desc')
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
        $add = new Itemlevelscheme();
        $add->loc_code = $request->loc_code;
        $add->promo_code = $request->promo_code;
        $add->item_code = $request->item_code;
        $add->batch_no = $request->batch_no;
        $add->from_date = $request->from_date;
        $add->to_date = $request->to_date;
        $add->from_time = $request->from_time;
        $add->to_time = $request->to_time;
        $add->from_qty = $request->from_qty;
        $add->to_qty = $request->to_qty;
        $add->max_qty = $request->max_qty;
        $add->disc_perc = $request->disc_perc;
        $add->disc_amt = $request->disc_amt;
        $add->fix_rate = $request->fix_rate;
        $add->calc_on = $request->calc_on;
        $add->cust_type_incl = $request->cust_type_incl;
        $add->cust_type_excl = $request->cust_type_excl;
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
        $edit = Itemlevelscheme::find($id);
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
        $update = Itemlevelscheme::find($request->_id);
        $update->loc_code = $request->loc_code;
        $update->promo_code = $request->promo_code;
        $update->item_code = $request->item_code;
        $update->batch_no = $request->batch_no;
        $update->from_date = $request->from_date;
        $update->to_date = $request->to_date;
        $update->from_time = $request->from_time;
        $update->to_time = $request->to_time;
        $update->from_qty = $request->from_qty;
        $update->to_qty = $request->to_qty;
        $update->max_qty = $request->max_qty;
        $update->disc_perc = $request->disc_perc;
        $update->disc_amt = $request->disc_amt;
        $update->fix_rate = $request->fix_rate;
        $update->calc_on = $request->calc_on;
        $update->cust_type_incl = $request->cust_type_incl;
        $update->cust_type_excl = $request->cust_type_excl;
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
        $delete = Itemlevelscheme::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
