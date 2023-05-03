<?php

namespace App\Repositories;

use App\Interfaces\SkillsInterface;
use App\Models\Skiils;

class SkiilsRepository implements SkillsInterface
{

    public function getAll($select = [], $search = null, $sortOption = [], $paginateOption = [],  $reformat = null)
    {
        $skills = Skiils::query();
        if (isset($select) && count($select) > 0) {
            $skills->addSelect($select);
        }
        if (isset($search) && is_callable($search)) {
            $skills->where($search);
        }
        if (isset($sortOption['orderCol']) && !empty($sortOption['orderCol'])) {
            $skills->orderBy($sortOption['orderCol'], $sortOption['orderDir'] ?? 'ASC');
        }
        if (isset($paginateOption['method']) && !empty($paginateOption['method'])) {
            $skills =  $skills->{$paginateOption['method']}(perPage: $paginateOption['length'] ?? 10, page: $paginateOption['page'] ?? null);
        } else {
            $skills =  $skills->get();
        }
        if (isset($reformat) && is_callable($reformat)) {
            $skills = $reformat($skills);
        }
        return $skills;
    }
    //pake find normal
    public function findById($id, $withRelations = [], $method = 'find')
    {
        $skills = Skiils::with($withRelations)->$method($id);
        return $skills;
    }

    public function findByIdHash($id, $withRelations = [], $method = 'firstOrFail')
    {
        $skills = Skiils::with($withRelations)->where('id_hash', $id)->$method();
        return $skills;
    }

    public function create($data)
    {
        $skills = new Skiils();
        foreach ($data as $key => $value) {
            $skills->$key = $value;
        }
        $skills->save();
        return $skills;
    }

    public function updateById($id, $data)
    {
        $skills = $this->findById($id);
        foreach ($data as $key => $value) {
            $skills->$key = $value;
        }
        $skills->save();
        return $skills;
    }

    public function updateByIdHash($id, $data)
    {
        $skills = $this->findByIdHash($id);
        foreach ($data as $key => $value) {
            $skills->$key = $value;
        }
        $skills->save();
        return $skills;
    }

    public function deletedById($id)
    {
        $skills = $this->findById($id);
        return $skills->delete();
    }

    public function deletedByIdHash($id)
    {
        $skills = $this->findByIdHash($id);
        return $skills->delete();
    }
}
