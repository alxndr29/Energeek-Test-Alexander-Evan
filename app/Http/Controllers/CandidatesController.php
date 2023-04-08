<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidates;
use Illuminate\Support\Facades\Validator;
use App\Models\Skiils;
use Illuminate\Support\Facades\DB;

class CandidatesController extends Controller
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
        //
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'email' => 'required|unique:candidates|email',
            'telepon' => 'required|unique:candidates|numeric',
            'year' => 'required|numeric',
            'skill_id' => 'required',
            'job_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'error_message' => $validator->errors()
                ],
                400
            );
        }
        DB::beginTransaction();
        try {
            $candidates = Candidates::create([
                'name'   => $request->name,
                'email' =>  $request->email,
                'telepon' =>  $request->telepon,
                'year' =>  $request->year,
                'job_id' =>  $request->job_id
            ]);
            foreach ($request->skill_id as $value) {
                $candidates->skills()->attach($value);
            }
            if ($candidates) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Candidate success Created',
                    'data'    => $candidates
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409);
        }
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
