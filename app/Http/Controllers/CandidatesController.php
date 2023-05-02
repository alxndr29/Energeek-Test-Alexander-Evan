<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidates;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Builder;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\CandidatesResource;
use App\Http\Resources\JobsResource;
use App\Interfaces\CandidatesInterface;
use App\Interfaces\JobsInterface;

class CandidatesController extends Controller
{
    private $candidatesInterface;
    public function __construct(CandidatesInterface $candidatesInterface)
    {
        $this->candidatesInterface = $candidatesInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *   tags={"API|MASTER|INDEX CANDIDATES"},
     *   path="/api/candidates",
     *   summary="Candidates index",
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
        //
        $jobs = $this->candidatesInterface->getAll(
            select: [
                'id',
                'id_hash',
                'job_id',
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

    /**
     * @OA\Post(
     *   tags={"Api|Master|STORE CANDIDATES"},
     *   path="/api/candidates/store/",
     *   summary="Jobs store",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name","email","telepon","year","skill_id","job_id"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="telepon", type="number"),
     *       @OA\Property(property="year", type="number"),
     *       @OA\Property(property="skill_id", type="string"),
     *       @OA\Property(property="job_id", type="number"),
     *     )
     *   ),
    
     *   @OA\Response(response="default", ref="#/components/responses/globalResponse")
     * )
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:candidates|email',
            'telepon' => 'required|unique:candidates|numeric',
            'year' => 'required|numeric',
            'skill_id' => 'required',
            'job_id' => 'required'
        ];
        $messages = [];
        $attributes = [
            'name' => 'Nama',
            'email' => 'Email',
            'telepon' => 'Telepon',
            'year' => 'Tahun',
            'skill_id' => 'skill_id',
            'job_id' => 'job_id'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            DB::rollBack();
            return ResponseFormatter::error([
                'errors' => $validator->errors()
            ], 'Silahkan isi form dengan benar terlebih dahulu', 422);
        }
        $candidates = $this->candidatesInterface->create(
            $request->all()
        );
        DB::commit();
        return (new CandidatesResource($candidates))->additional([
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
     *   tags={"API|MASTER|SHOW CANDIDATES"},
     *   path="/api/candidates/show/{id}",
     *   summary="Candidates show",
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
        $candidates = $this->candidatesInterface->findByIdHash(
            $id,
            ['skills', 'jobs']
        );
        if (empty($candidates)) {
            return ResponseFormatter::error([], 'Data tidak ditemukan', 404);
        }
        // return ResponseFormatter::success($candidates, 'Data berhasil ditampilkan');

        return (new CandidatesResource($candidates))->additional([
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
     *   tags={"Api|MASTER|UPDATE CANDIDATES"},
     *   path="/api/candidates/update/{id}",
     *   summary="Jobs update",
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
     *       required={"name","email","telepon","year","skill_id","job_id"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="telepon", type="number"),
     *       @OA\Property(property="year", type="number"),
     *       @OA\Property(property="skill_id", type="string"),
     *       @OA\Property(property="job_id", type="number"),
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
            'name' => 'required',
            'email' => 'required|unique:candidates|email',
            'telepon' => 'required|unique:candidates|numeric',
            'year' => 'required|numeric',
            'skill_id' => 'required',
            'job_id' => 'required'
        ];
        $messages = [];
        $attributes = [
            'name' => 'Nama',
            'email' => 'Email',
            'telepon' => 'Telepon',
            'year' => 'Tahun',
            'skill_id' => 'skill_id',
            'job_id' => 'job_id'
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            DB::rollBack();
            return ResponseFormatter::error([
                'errors' => $validator->errors()
            ], 'Silahkan isi form dengan benar terlebih dahulu', 422);
        }
        $candidates = $this->candidatesInterface->updateByIdHash(
            $id,
            $request->all()
        );
        DB::commit();
        return (new CandidatesResource($candidates))->additional([
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
     *   tags={"Api|Master|DELETE CANDIDATES"},
     *   path="/api/candidates/delete/{id}",
     *   summary="Candidates Delete",
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
        $candidates = $this->candidatesInterface->deletedByIdHash($id);
        if (empty($candidates)) {
            DB::rollBack();
            return ResponseFormatter::error([], 'Data gagal dihapus', 400);
        }
        DB::commit();
        return ResponseFormatter::success($candidates, 'Data berhasil dihapus', 200);
    }
}
