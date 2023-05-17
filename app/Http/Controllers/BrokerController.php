<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broker;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = Broker::get();
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
        $add = new Broker();
        $add->broker_code = $request->broker_code;
        $add->broker_name = $request->broker_name;
        $add->gl_code = $request->gl_code;
        $add->comm_perc = $request->comm_perc;
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
        $edit = Broker::find($id);
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
        $update = Broker::find($request->_id);
        $update->broker_code = $request->broker_code;
        $update->broker_name = $request->broker_name;
        $update->gl_code = $request->gl_code;
        $update->comm_perc = $request->comm_perc;
        $update->status = $request->status;
        $update->created_by = $request->created_by;
        $update->created_at = now();
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
        $delete = Broker::find($id);
        $delete->delete();
        return response()->json(['success' => true], 200);
    }
}
