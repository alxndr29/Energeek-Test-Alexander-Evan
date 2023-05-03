<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CandidatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id_hash,
            'name' => $this->name,
            'email' => $this->email,
            'year' => $this->year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'skills' => SkillsResource::collection($this->whenLoaded('skills')) ?? null,
            'jobs' => new JobsResource($this->whenLoaded('jobs')) ?? null,
            'pivot' => $this->whenPivotLoaded('skills_sets', function () {
                return [
                    'id' => $this->pivot->skill_id
                ];
            })
        ];
        return $result;
    }
}
