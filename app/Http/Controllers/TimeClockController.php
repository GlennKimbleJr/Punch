<?php

namespace App\Http\Controllers;

use App\Clock;
use Illuminate\Http\Request;
use App\Reports\TimeSheetReport;
use App\Http\Requests\TimeSheetRequest;

class TimeClockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\TimeSheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(TimeSheetRequest $request)
    {
        return (new TimeSheetReport($request, auth()->user()))->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Clock::toggle();
    }
}
