<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;

class VendorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Vendor::get();
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
                    $evaluators     = new Vendor();
                    if ($data_query_setting_value == 'count') {
                        $evaluators = $evaluators->select('vendormasters._id');
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
                        $evaluators         = $evaluators->orderBy('vendormasters._id', 'desc')
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
        $add = new Vendor();
        $add->vend_code = $request->vend_code;
        $add->vend_name = $request->vend_name;
        $add->type = $request->type;
        $add->credit_day = $request->credit_day;
        $add->aadr1 = $request->aadr1;
        $add->aadr2 = $request->aadr2;
        $add->city = $request->city;
        $add->state = $request->state;
        $add->country = $request->country;
        $add->pin_no = $request->pin_no;
        $add->phone = $request->phone;
        $add->email = $request->email;
        $add->gstin = $request->gstin;
        $add->fassi_no = $request->fassi_no;
        $add->aadhar_no = $request->aadhar_no;
        $add->pan_no = $request->pan_no;
        $add->contact_person = $request->contact_person;
        $add->addr1 = $request->addr1;
        $add->addr2 = $request->addr2;
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

    public function vendor_code(Request $request, $code)
    {
        $data = Vendor::where('vend_code', $code)->first();
        return response()->json(['data' => array($data)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = Vendor::find($id);
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
        $update = Vendor::find($request->_id);
        $update->vend_code = $request->vend_code;
        $update->vend_name = $request->vend_name;
        $update->type = $request->type;
        $update->credit_day = $request->credit_day;
        $update->aadr1 = $request->aadr1;
        $update->aadr2 = $request->aadr2;
        $update->city = $request->city;
        $update->state = $request->state;
        $update->country = $request->country;
        $update->pin_no = $request->pin_no;
        $update->phone = $request->phone;
        $update->email = $request->email;
        $update->gstin = $request->gstin;
        $update->fassi_no = $request->fassi_no;
        $update->aadhar_no = $request->aadhar_no;
        $update->pan_no = $request->pan_no;
        $update->contact_person = $request->contact_person;
        $update->addr1 = $request->addr1;
        $update->addr2 = $request->addr2;
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
        $delete = Vendor::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
