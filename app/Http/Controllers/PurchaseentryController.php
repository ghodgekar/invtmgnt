<?php

namespace App\Http\Controllers;

use App\Purchaseentry;
use App\Purchasedetail;
use App\Purchaseheader;
use App\Item;
use App\Itembarcode;
use App\Tax;
use App\Openingstock;
use App\Transactiondetail;
use App\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PurchaseentryController extends Controller
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
        $lastdata = Purchaseheader::orderBy('id', 'desc')->first();
        if($lastdata){
            $batchno = (int)$lastdata->batch_no + 1;
        }else{
            $batchno = 00;
        }

        $branch = Branch::where('loc_code', $request->loc_code)->first();
        $add = new Purchaseheader();
        $add->loc_code = $request->loc_code;
        $add->comp_code = $branch->comp_code;
        $add->v_no = $request->gnr_no;
        $add->v_date = date('Y-m-d H:s:i', strtotime($request->v_date));
        $add->invoice_type = $request->invoice_type;
        $add->po_no = $request->po_no;
        $add->po_date = date('Y-m-d H:s:i', strtotime($request->po_date));
        $add->gi_no = $request->gi_no;
        $add->supp_code = $request->supplier;
        $add->supp_billno = $request->bill_no;
        $add->supp_billdate = date('Y-m-d H:s:i', strtotime($request->bill_date));
        $add->gstin = $request->gstin;
        $add->broker = $request->broker;
        $add->narration = $request->narration;
        $add->taxable_amt = $request->taxable_amt;
        $add->tax_amt = $request->tax_amt;
        $add->apmc_amt = $request->apmc;
        $add->item_disc = $request->item_desc;
        $add->cash_disc = $request->cash_disc;
        $add->pur_amt = $request->pur_amt;
        $add->round_off = $request->r_off;
        $add->supp_bill_amt = $request->supp_bill_amt;
        $add->extra_disc = $request->extra_desc;
        $add->extra_chrg = $request->extra_charge;
        $add->oth_chrg = $request->other_charge;
        $add->purret_amt = $request->pur_return;
        $add->net_pur_amt = $request->net_purchase;
        $add->eway_billno = $request->eway_billno;
        $add->eway_billdate = date('Y-m-d H:s:i', strtotime($request->eway_billdate));
        $add->status = $request->status;
        $add->batch_no = $batchno;
        $add->created_by = $request->created_by;
        $add->created_at = date('Y-m-d H:s:i', strtotime($request->created_at));
        $add->updated_by = $request->created_by;
        $add->updated_at = date('Y-m-d H:s:i', strtotime($request->updated_at));
        $add->lock_flag = $request->lock_flag;
        $add->locked_by = $request->auth_by;
        $add->save();
        foreach($request->barcodes as $key => $value){
            if($value['barcode']){
                $details = new Purchasedetail();
                $details->pm_id = $add->_id;
                $details->barcode = $value['barcode'];
                $details->item_code = $value['item_code'];
                $details->pkcase = $value['pk_case'];
                $details->qtypk = $value['qty_per_case'];
                $details->billqty = $value['bill_qty'];
                $details->freeqty = $value['free_qty'];
                $details->item_name = $value['item_name'];
                $details->totalqty = $value['total_qty'];
                $details->mrp = $value['mrp'];
                $details->billrate = $value['bill_rate'];
                $details->taxableamt = $value['taxable_amount'];
                $details->discper = $value['disc_percent'];
                $details->itmdiscamt = $value['item_disc'];
                $details->cashdisc = $value['cash_disc'];
                $details->tax = $value['tax_cd'];
                $details->taxamt = $value['tax_amt'];
                $details->landingcost = $value['landing_cost'];
                $details->salerate = $value['sale_rate'];
                $details->purchaseamt = $value['purchase_amt'];
                $details->expirydate = $value['expiry_date'];
                $details->mnfdate = $value['mfg_date'];
                $details->narration = $value['narration'];
                $details->save();
            }
        }
        return response()->json(['success' => true, 'data' => $add], 200);
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
        $delete = Parameter::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }

    public function barcode(Request $request, $barcode)
    {
        $itembarcode = Itembarcode::where('barcode', $barcode)->first();
        if($itembarcode){
            $data = Item::where('item_code', $itembarcode->item_code)->first();
            $tax = Tax::where('tax_code', $data->tax_code)->first();
            $data->{'tax'} = $tax;
            $data->{'barcode'} = $barcode;
            $openingstock = Transactiondetail::where('barcode', $barcode)->get();
            $data->{'openingstock'} = $openingstock;
            return response()->json(['data' => $data], 200);
        }else{
            $data = '';
            return response()->json(['data' => $data], 301);
        }
    }
}
