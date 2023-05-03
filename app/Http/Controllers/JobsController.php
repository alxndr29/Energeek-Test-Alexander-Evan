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
     *   tags={"API|MASTER|JOBS"},
     *   path="/api/jobs",
     *   summary="Jobs index",
     *     @OA\Parameter(
     *     name="search",
     *     in="query",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="sortBy",
     *     in="query",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="orderBy",
     *     in="query",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="currentPage",
     *     in="query",
     *     @OA\Schema(type="integer")
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
                $query->where('name', 'ILIKE', "%{$request->search}%");
            },
            sortOption: [
                'orderCol' => $request->sortBy,
                'orderDir' => $request->orderBy
            ],
            paginateOption: [
                'method' => 'paginate',
                'length' => $request->limit,
                'page' => $request->currentPage
            ],
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

    /**
     * @OA\Post(
     *   tags={"API|MASTER|JOBS"},
     *   path="/api/jobs/store/",
     *   summary="Jobs store",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name"},
     *       @OA\Property(property="name", type="string")
     *     )
     *   ),
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        $rules = [
            'name' => 'required'
        ];
        $messages = [];
        $attributes = [
            'name' => 'Nama'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            DB::rollBack();
            return ResponseFormatter::error([
                'errors' => $validator->errors()
            ], 'Silahkan isi form dengan benar terlebih dahulu', 422);
        }
        $jobs = $this->jobsInterface->create(
            $request->all()
        );
        DB::commit();
        return (new JobsResource($jobs))->additional([
            'status' => [
                'code' => 200,
                'message' => 'Data berhasil disimpan'
            ]
        ])->response()->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *   tags={"API|MASTER|JOBS"},
     *   path="/api/jobs/show/{id}",
     *   summary="Jobs show",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */
    public function show($id)
    {
        //
        $jobs = $this->jobsInterface->findByIdHash(
            $id
        );
        if (empty($jobs)) {
            return ResponseFormatter::error([], 'Data tidak ditemukan', 404);
        }
        return (new JobsResource($jobs))->additional([
            'status' => [
                'code' => 200,
                'message' => 'Data berhasil ditampilkan'
            ]
        ])->response()->setStatusCode(200);
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
    /**
     * @OA\Put(
     *   tags={"API|MASTER|JOBS"},
     *   path="/api/jobs/update/{id}",
     *   summary="Candidates update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name"},
     *       @OA\Property(property="name", type="string")
     *     )
     *   ),
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        $rules = [
            'name' => 'required'
        ];
        $messages = [];
        $attributes = [
            'name' => 'Nama'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            DB::rollBack();
            return ResponseFormatter::error([
                'errors' => $validator->errors()
            ], 'Silahkan isi form dengan benar terlebih dahulu', 422);
        }
        $jobs = $this->jobsInterface->updateByIdHash(
            $id,
            $request->all()
        );
        DB::commit();
        return (new JobsResource($jobs))->additional([
            'status' => [
                'code' => 200,
                'message' => 'Data berhasil disimpan'
            ]
        ])->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     *   tags={"API|MASTER|JOBS"},
     *   path="/api/jobs/delete/{id}",
     *   summary="Jobs delete-file",
     *   @OA\Parameter(
     *   name="id",
     *   in="path",
     *   required=true,
     *   @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */

    public function destroy($id)
    {
        //
        DB::beginTransaction();
        $jobs = $this->jobsInterface->deletedByIdHash($id);
        if (empty($jobs)) {
            DB::rollBack();
            return ResponseFormatter::error([], 'Data gagal dihapus', 400);
        }
        DB::commit();
        return ResponseFormatter::success($jobs, 'Data berhasil dihapus', 200);
    }
}
