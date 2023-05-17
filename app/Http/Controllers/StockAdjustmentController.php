<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Openingstock;
use App\Transactiondetail;
use App\Stockadjustmentheader;
use App\Stockadjustmentdetail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request->all());
        $add = new Stockadjustmentheader();
        $add->loc_code = $request->loc_code;
        $add->comp_code = $request->comp_code;
        $add->dept_code = $request->dept_name;
        $add->v_no = $request->vouch_no;
        $add->v_date = date($request->v_date);
        $add->pur_v_no = $request->pur_v_no;
        $add->adj_type = $request->adjmnt_type;
        $add->narration = $request->narration;
        $add->created_by = $request->created_by;
        $add->created_at = now();
        $add->updated_by = $request->updated_by;
        $add->updated_at = date($request->updated_at);
        $add->status = $request->status;
        $add->save();
        foreach($request->barcodes as $key => $value){
            if($value['sku_code'] || $value['sku_code'] != ''){
                $details = new Stockadjustmentdetail();
                $details->stock_head_id = $add->_id;
                $details->item_code = $value['sku_code'];
                $details->item_name = $value['sku_name'];
                $details->reason_code = $value['reason'];
                $details->rec_iss = $value['rec_iss'];
                $details->qty = $value['qty'];
                $details->lot_no = $value['lot_no'];
                $details->bal_qty = $value['bal_qty'];
                $details->sale_rate = $value['sale_rate'];
                $details->mrp = $value['mrp'];
                $details->cost_rate = $value['cost_rate'];
                $details->batch_no = $value['batch_no'];
                $details->expiry_date = $value['expiry_date'];
                $details->save();
            }
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
