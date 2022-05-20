<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDaysTimeRequest;
use App\Http\Requests\UpdateDaysTimeRequest;
use App\Models\DaysTime;

class DaysTimeController extends Controller
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
     * @param  \App\Http\Requests\StoreDaysTimeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDaysTimeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DaysTime  $daysTime
     * @return \Illuminate\Http\Response
     */
    public function show(DaysTime $daysTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DaysTime  $daysTime
     * @return \Illuminate\Http\Response
     */
    public function edit(DaysTime $daysTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDaysTimeRequest  $request
     * @param  \App\Models\DaysTime  $daysTime
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDaysTimeRequest $request, DaysTime $daysTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DaysTime  $daysTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(DaysTime $daysTime)
    {
        //
    }
}
