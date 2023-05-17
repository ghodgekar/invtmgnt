<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Openingstock;
use App\Transactiondetail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OpeningstockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Openingstock::get();
        return response()->json(['lists' => $lists]);
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
        $batch_no = '';
        $stock = Openingstock::where('barcode', $request->barcode)->where('mrp', $request->mrp)->where('sale_rate', $request->sale_rate)->where('cost_rate', $request->cost_rate)->first();
        if($stock){
            $stock->qty = (int)($stock->qty + ($request->qty));
            $stock->save();
        }else{
            $stock = Transactiondetail::where('barcode', $request->barcode)->orWhere('mrp', $request->mrp)->orWhere('sale_rate', $request->sale_rate)->orWhere('cost_rate', $request->cost_rate)->first();
            if($stock){
                if($request->item_type == 'P' || $request->item_type == 'Pack' || $request->item_type == 'V' || $request->item_type == 'Variant'){
                    $batch_no = (int)($stock->batch_no - 1);
                    $add = new Openingstock();
                    $add->loc_code = $request->loc_code;
                    $add->barcode = $request->barcode;
                    $add->item_code = $request->item_code;
                    $add->qty = $request->qty;
                    $add->mrp = round($request->mrp, 3);
                    $add->sale_rate = round($request->sale_rate, 3);
                    $add->cost_rate = round($request->cost_rate, 3);
                    $add->dept_code = $request->dept_code;
                    $add->expiry_date = $request->expiry_date;
                    $add->batch_no = $batch_no;
                    $add->doc_type = $request->doc_type;
                    $add->comp_code = $request->comp_code;
                    $add->item_name = $request->item_name;
                    $add->markup = $request->markup;
                    $add->markdown = $request->markdown;
                    $add->status = $request->status;
                    $add->created_by = $request->created_by;
                    $add->updated_by = $request->created_by;
                    $add->created_at = now();
                    $add->save();
                }elseif($request->item_type == 'L' || $request->item_type == 'Loose'){
                    $batch_no = (int)($stock->batch_no - 1);
                    $add = new Openingstock();
                    $add->loc_code = $request->loc_code;
                    $add->barcode = $request->barcode;
                    $add->item_code = $request->item_code;
                    $add->qty = $request->qty;
                    $add->mrp = round($request->mrp, 3);
                    $add->sale_rate = round($request->sale_rate, 3);
                    $add->cost_rate = round($request->cost_rate, 3);
                    $add->dept_code = $request->dept_code;
                    $add->expiry_date = $request->expiry_date;
                    $add->batch_no = $batch_no;
                    $add->doc_type = $request->doc_type;
                    $add->comp_code = $request->comp_code;
                    $add->item_name = $request->item_name;
                    $add->markup = $request->markup;
                    $add->markdown = $request->markdown;
                    $add->status = $request->status;
                    $add->created_by = $request->created_by;
                    $add->updated_by = $request->created_by;
                    $add->created_at = now();
                    $add->save();
                }
            }else{
                if($request->item_type == 'P' || $request->item_type == 'Pack'){
                    $batch_no = -1;
                }elseif($request->item_type == 'L' || $request->item_type == 'Loose'){
                    $batch_no = -99;
                }
                $add = new Openingstock();
                $add->loc_code = $request->loc_code;
                $add->barcode = $request->barcode;
                $add->item_code = $request->item_code;
                $add->qty = $request->qty;
                $add->mrp = round($request->mrp, 3);
                $add->sale_rate = round($request->sale_rate, 3);
                $add->cost_rate = round($request->cost_rate, 3);
                $add->dept_code = $request->dept_code;
                $add->expiry_date = $request->expiry_date;
                $add->batch_no = $batch_no;
                $add->doc_type = $request->doc_type;
                $add->comp_code = $request->comp_code;
                $add->item_name = $request->item_name;
                $add->markup = $request->markup;
                $add->markdown = $request->markdown;
                $add->status = $request->status;
                $add->created_by = $request->created_by;
                $add->updated_by = $request->created_by;
                $add->created_at = now();
                $add->save();
            }
        }

        $stock = Transactiondetail::where('barcode', $request->barcode)->where('mrp', $request->mrp)->where('sale_rate', $request->sale_rate)->where('cost_rate', $request->cost_rate)->first();
        if($stock){
            $stock->qty = (int)($stock->qty + ($request->qty));
            $stock->save();
        }else{
            $stock = Transactiondetail::where('barcode', $request->barcode)->orWhere('mrp', $request->mrp)->orWhere('sale_rate', $request->sale_rate)->orWhere('cost_rate', $request->cost_rate)->first();
            if($stock){
                if($request->item_type == 'P' || $request->item_type == 'Pack' || $request->item_type == 'V' || $request->item_type == 'Variant'){
                    $batch_no = (int)($stock->batch_no - 1);
                    $trans = new Transactiondetail();
                    $trans->stock_id = $add->_id;
                    $trans->loc_code = $request->loc_code;
                    $trans->barcode = $request->barcode;
                    $trans->item_code = $request->item_code;
                    $trans->qty = $request->qty;
                    $trans->mrp = round($request->mrp, 3);
                    $trans->sale_rate = round($request->sale_rate, 3);
                    $trans->cost_rate = round($request->cost_rate, 3);
                    $trans->dept_code = $request->dept_code;
                    $trans->expiry_date = $request->expiry_date;
                    $trans->batch_no = $batch_no;
                    $trans->doc_type = $request->doc_type;
                    $trans->comp_code = $request->comp_code;
                    $trans->item_name = $request->item_name;
                    $trans->markup = $request->markup;
                    $trans->markdown = $request->markdown;
                    $trans->status = $request->status;
                    $trans->created_by = $request->created_by;
                    $trans->updated_by = $request->created_by;
                    $trans->created_at = now();
                    $trans->save();
                }elseif($request->item_type == 'L' || $request->item_type == 'Loose'){
                    $batch_no = (int)($stock->batch_no - 1);
                    $trans = new Transactiondetail();
                    $trans->stock_id = $add->_id;
                    $trans->loc_code = $request->loc_code;
                    $trans->barcode = $request->barcode;
                    $trans->item_code = $request->item_code;
                    $trans->qty = $request->qty;
                    $trans->mrp = round($request->mrp, 3);
                    $trans->sale_rate = round($request->sale_rate, 3);
                    $trans->cost_rate = round($request->cost_rate, 3);
                    $trans->dept_code = $request->dept_code;
                    $trans->expiry_date = $request->expiry_date;
                    $trans->batch_no = $batch_no;
                    $trans->doc_type = $request->doc_type;
                    $trans->comp_code = $request->comp_code;
                    $trans->item_name = $request->item_name;
                    $trans->markup = $request->markup;
                    $trans->markdown = $request->markdown;
                    $trans->status = $request->status;
                    $trans->created_by = $request->created_by;
                    $trans->updated_by = $request->created_by;
                    $trans->created_at = now();
                    $trans->save();
                }
            }else{
                if($request->item_type == 'P' || $request->item_type == 'Pack' || $request->item_type == 'V' || $request->item_type == 'Variant'){
                    $batch_no = -1;
                }elseif($request->item_type == 'L' || $request->item_type == 'Loose'){
                    $batch_no = -99;
                }
                $trans = new Transactiondetail();
                $trans->stock_id = $add->_id;
                $trans->loc_code = $request->loc_code;
                $trans->barcode = $request->barcode;
                $trans->item_code = $request->item_code;
                $trans->qty = $request->qty;
                $trans->mrp = round($request->mrp, 3);
                $trans->sale_rate = round($request->sale_rate, 3);
                $trans->cost_rate = round($request->cost_rate, 3);
                $trans->dept_code = $request->dept_code;
                $trans->expiry_date = $request->expiry_date;
                $trans->batch_no = $batch_no;
                $trans->doc_type = $request->doc_type;
                $trans->comp_code = $request->comp_code;
                $trans->item_name = $request->item_name;
                $trans->markup = $request->markup;
                $trans->markdown = $request->markdown;
                $trans->status = $request->status;
                $trans->created_by = $request->created_by;
                $trans->updated_by = $request->created_by;
                $trans->created_at = now();
                $trans->save();
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
        $edit = Openingstock::find($id);
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
        $update = Openingstock::find($request->_id);
        $update->loc_code = $request->loc_code;
        $update->barcode = $request->barcode;
        $update->item_code = $request->item_code;
        $update->qty = $request->qty;
        $update->mrp = round($request->mrp, 3);
        $update->sale_rate = round($request->sale_rate, 3);
        $update->cost_rate = round($request->cost_rate, 3);
        $update->dept_code = $request->dept_code;
        $update->expiry_date = $request->expiry_date;
        $update->item_name = $request->item_name;
        $update->markup = $request->markup;
        $update->markdown = $request->markdown;
        $update->status = $request->status;
        $update->save();

        $trans = Transactiondetail::where('stock_id', $request->_id)->first();
        $trans->loc_code = $request->loc_code;
        $trans->barcode = $request->barcode;
        $trans->item_code = $request->item_code;
        $trans->qty = $request->qty;
        $trans->mrp = round($request->mrp, 3);
        $trans->sale_rate = round($request->sale_rate, 3);
        $trans->cost_rate = round($request->cost_rate, 3);
        $trans->dept_code = $request->dept_code;
        $trans->expiry_date = $request->expiry_date;
        $trans->item_name = $request->item_name;
        $trans->markup = $request->markup;
        $trans->markdown = $request->markdown;
        $trans->status = $request->status;
        $trans->save();

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
        $delete = Openingstock::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }

    public function lot_no_change(Request $request, $lot, $barcode)
    {
        $lot = Transactiondetail::where('batch_no', $lot)->where('barcode', $barcode)->first();
        return response()->json(['lot' => $lot]);
    }
}
