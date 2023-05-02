<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use App\Models\Jobs;

use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Resources\JobsResource;
use App\Interfaces\JobsInterface;
class JobsController extends Controller
{
    private $jobsInterface;
    public function __construct(JobsInterface $jobsInterface)
    {
        $this->jobsInterface = $jobsInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
     /**
     * @OA\Get(
     *   tags={"API|DATA|INDEX JOBS"},
     *   path="/api/jobs",
     *   summary="Jobs index",
     *     @OA\Parameter(
     *     name="search",
     *     in="query",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */

    public function index(Request $request)
    {
        $jobs = $this->jobsInterface->getAll(
            select: [
                'id',
                'id_hash',
                'name',
                'created_at',
                'updated_at'
            ],
            search: function (Builder $query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }
        );
        return ResponseFormatter::success($jobs, 'Data berhasil ditampilkan');
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
