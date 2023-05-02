<?php

namespace App\Http\Controllers;

use App\Models\Skiils;
use Illuminate\Http\Request;

use App\Helpers\ResponseFormatter;
use App\Helpers\Query;
use App\Http\Resources\SkillsResource;
use App\Interfaces\SkillsInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
class SkillsController extends Controller
{
    private $skillInterface;
    public function __construct(SkillsInterface $skillInterface)
    {
        $this->skillInterface = $skillInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *   tags={"API|DATA|INDEX SKILLS"},
     *   path="/api/skills",
     *   summary="Skills index",
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
        $skill = $this->skillInterface->getAll(
            [
                'id',
                'name',
                'created_at',
                'updated_at'
            ],
            function (Builder $query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }
        );
        return ResponseFormatter::success($skill, 'Data berhasil ditampilkan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *   tags={"Api|Master|STORE SKILLS"},
     *   path="/api/skills/store/",
     *   summary="Skills store",
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
        $skill = $this->skillInterface->create(
            $request->all()
        );
        DB::commit();
        return (new SkillsResource($skill))->additional([
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
     *   tags={"API|DATA|SHOW SKILLS"},
     *   path="/api/skills/show/{id}",
     *   summary="Skills show",
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
        $skill = $this->skillInterface->findById(
            $id
        );
        if (empty($skill)) {
            return ResponseFormatter::error([], 'Data tidak ditemukan', 404);
        }
        return (new SkillsResource($skill))->additional([
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
     *   tags={"Api|Master|UPDATE SKILLS"},
     *   path="/api/skills/update/{id}",
     *   summary="TourismCategory update",
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
        $skill = $this->skillInterface->updateById(
            $id,
            $request->all()
        );
        DB::commit();
        return (new SkillsResource($skill))->additional([
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
     *   tags={"Api|Master|DELETE SKILLS"},
     *   path="/api/skills/delete/{id}",
     *   summary="Skills delete-file",
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
        DB::beginTransaction();
        $skill = $this->skillInterface->deletedById($id);
        if (empty($skill)) {
            DB::rollBack();
            return ResponseFormatter::error([], 'Data gagal dihapus', 400);
        }
        DB::commit();
        return ResponseFormatter::success($skill, 'Data berhasil dihapus', 200);
    }
}
