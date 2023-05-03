<?php

namespace App\Repositories;

use App\Interfaces\CandidatesInterface;
use App\Models\Candidates;

class CandidatesRepository implements CandidatesInterface
{
    public function getAll($select = [], $search = null, $sortOption = [], $paginateOption = [],  $reformat = null)
    {
        $candidates = Candidates::query();
        
        if (isset($select) && count($select) > 0) {
            $candidates->addSelect($select);
        }
        if (isset($search) && is_callable($search)) {
            $candidates->where($search);
        }
        if (isset($sortOption['orderCol']) && !empty($sortOption['orderCol'])) {
            $candidates->orderBy($sortOption['orderCol'], $sortOption['orderDir'] ?? 'ASC');
        }
        if (isset($paginateOption['method']) && !empty($paginateOption['method'])) {
            $candidates = $candidates->{$paginateOption['method']}(perPage: $paginateOption['length'] ?? 10, page: $paginateOption['page'] ?? null);
        } else {
            $candidates = $candidates->get();
        }
        if (isset($reformat) && is_callable($reformat)) {
            $candidates = $reformat($candidates);
        }
        return $candidates;
    }
    public function create($data)
    {
        $candidates = new Candidates();
        $candidates->name = $data['name'];
        $candidates->email = $data['email'];
        $candidates->telepon = $data['telepon'];
        $candidates->year = $data['year'];
        $candidates->job_id = $data['job_id'];
        $candidates->save();

        foreach ($data['skill_id'] as $value) {
            $candidates->skills()->attach($value);
        }
        return $candidates;
    }
    public function updateById($id, $data)
    {
        $candidates = $this->findById($id);

        $candidates->name = $data['name'];
        $candidates->email = $data['email'];
        $candidates->telepon = $data['telepon'];
        $candidates->year = $data['year'];
        $candidates->job_id = $data['job_id'];
        $candidates->save();

        // foreach ($data['skill_id'] as $value) {
        $candidates->skills()->sync($data['skill_id']);
        // }
        return $candidates;
    }

    public function updateByIdHash($id, $data)
    {
        $candidates = $this->findByIdHash($id);

        $candidates->name = $data['name'];
        $candidates->email = $data['email'];
        $candidates->telepon = $data['telepon'];
        $candidates->year = $data['year'];
        $candidates->job_id = $data['job_id'];
        $candidates->save();

        // foreach ($data['skill_id'] as $value) {
        $candidates->skills()->sync($data['skill_id']);
        // }
        return $candidates;
    }
    //pake find normal
    public function findById($id, $withRelations = [], $method = 'find')
    {
        $candidates = Candidates::with($withRelations)->$method($id);
        return $candidates;
    }

    public function findByIdHash($id, $withRelations = [], $method = 'firstOrFail')
    {
        $candidates = Candidates::with($withRelations)->where('id_hash', $id)->$method();
        return $candidates;
    }

    public function deletedById($id)
    {
        $candidates = $this->findById($id);
        return $candidates->delete();
    }

    public function deletedByIdHash($id)
    {
        $candidates = $this->findByIdHash($id);
        return $candidates->delete();
    }
}
