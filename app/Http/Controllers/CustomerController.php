<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Customer::get();
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
                    $evaluators     = new Customer();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('customermasters._id');
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
                        $evaluators         = $evaluators->orderBy('customermasters._id', 'desc')
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
        $add = new Customer();
        $add->cust_code = $request->cust_code;
        $add->cust_name = $request->cust_name;
        $add->gender = $request->gender;
        $add->addr1 = $request->addr1;
        $add->addr2 = $request->addr2;
        $add->city = $request->city;
        $add->state = $request->state;
        $add->country = $request->country;
        $add->pincode = $request->pincode;
        $add->mobile = $request->mobile;
        $add->email = $request->email;
        $add->aadhar_no = $request->aadhar_no;
        $add->pan_no = $request->pan_no;
        $add->gstin = $request->gstin;
        $add->birth_date = $request->birth_date;
        $add->join_date = $request->join_date;
        $add->cust_type = $request->cust_type;
        $add->barcode = $request->barcode;
        $add->points = $request->points;
        $add->ref_cust_code = $request->ref_cust_code;
        $add->cr_limit = $request->cr_limit;
        $add->cr_overdue_days = $request->cr_overdue_days;
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
        $edit = Customer::find($id);
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
        $update = Customer::find($request->_id);
        $update->cust_code = $request->cust_code;
        $update->cust_name = $request->cust_name;
        $update->gender = $request->gender;
        $update->addr1 = $request->addr1;
        $update->addr2 = $request->addr2;
        $update->city = $request->city;
        $update->state = $request->state;
        $update->country = $request->country;
        $update->pincode = $request->pincode;
        $update->mobile = $request->mobile;
        $update->email = $request->email;
        $update->aadhar_no = $request->aadhar_no;
        $update->pan_no = $request->pan_no;
        $update->gstin = $request->gstin;
        $update->birth_date = $request->birth_date;
        $update->join_date = $request->join_date;
        $update->cust_type = $request->cust_type;
        $update->barcode = $request->barcode;
        $update->points = $request->points;
        $update->ref_cust_code = $request->ref_cust_code;
        $update->cr_limit = $request->cr_limit;
        $update->cr_overdue_days = $request->cr_overdue_days;
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
        $delete = Customer::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
