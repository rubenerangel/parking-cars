<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reduction;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reductions = Reduction::all();
        
        return view( 'discounts', compact('reductions') );
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reduction = Reduction::find($id);

        return view('partials.discounts.showEdit', compact('reduction'));
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
        // dd($request->all());
        /* validate get Data */
        $validateReduction = $request->validate([
            'name'       => 'required',
            'percentage' => 'required',
            'time'       => 'required',
        ]);

        $reduction = Reduction::find($id);

        $reduction->name = $request->name;
        $reduction->percentage = $request->percentage;
        $reduction->time = $request->time;
        $reduction->active = isset($request->active) ? 1 : 0;
        
        $reduction->save();
        
        $message = 'Descuento actualizado correctamente';

        return view('partials.discounts.showEdit', compact(['message', 'reduction']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reductions = Reduction::find($id);
        // $discount->delete();

        return $this->index();
    }
}
