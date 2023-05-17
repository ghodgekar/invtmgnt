<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reason;
use App\Purchaseentry;
use App\Purchasedetail;
use App\Purchaseheader;
use App\Item;
use App\Itembarcode;
use App\Tax;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Reason::get();
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
        $add = new Reason();
        $add->reason_code = $request->reason_code;
        $add->reason_desc = $request->reason_desc;
        $add->rec_iss = $request->rec_iss;
        $add->new_adj = $request->new_adj;
        $add->sys_gen = $request->sys_gen;
        $add->status = $request->status;
        $add->created_by = $request->created_by;
        $add->created_at = now();
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
        $edit = Reason::find($id);
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
        $update = Reason::find($request->_id);
        $update->reason_code = $request->reason_code;
        $update->reason_desc = $request->reason_desc;
        $update->rec_iss = $request->rec_iss;
        $update->new_adj = $request->new_adj;
        $update->sys_gen = $request->sys_gen;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->created_at = now();
        $update->save();
        return response()->json(['success' => true], 200);
    }

    public function getReasonByCode(Request $request, $code)
    {
        $data = Reason::where('reason_code', $code)->first();
        return response()->json(['data' => array($data)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Reason::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
