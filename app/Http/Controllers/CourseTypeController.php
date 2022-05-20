<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseTypeRequest;
use App\Http\Requests\UpdateCourseTypeRequest;
use App\Models\CourseType;

class CourseTypeController extends Controller
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
     * @param  \App\Http\Requests\StoreCourseTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function show(CourseType $courseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseType $courseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCourseTypeRequest  $request
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseTypeRequest $request, CourseType $courseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseType $courseType)
    {
        //
    }
}
