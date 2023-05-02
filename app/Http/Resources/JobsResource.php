<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobsResource extends JsonResource
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
            'id_hash' => $this->id_hash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        return $result;
    }
}
