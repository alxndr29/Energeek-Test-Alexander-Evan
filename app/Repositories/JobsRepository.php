<?php

namespace App\Repositories;

use App\Interfaces\JobsInterface;
use App\Models\Jobs;

class JobsRepository implements JobsInterface
{

    public function getAll($select = [], $search = null)
    {
        $jobs = Jobs::query();
        if (isset($select) && count($select) > 0) {
            $jobs->addSelect($select);
        }
        if (isset($search) && is_callable($search)) {
            $jobs->where($search);
        }
        return $jobs->get();
    }
    //pake find normal
    public function findById($id, $withRelations = [], $method = 'find')
    {
        $jobs = Jobs::with($withRelations)->$method($id);
        return $jobs;
    }

    public function findByIdHash($id, $withRelations = [], $method = 'firstOrFail')
    {
        $jobs = Jobs::with($withRelations)->where('id_hash', $id)->$method();
        return $jobs;
    }

    public function create($data)
    {
        $jobs = new Jobs();
        foreach ($data as $key => $value) {
            $jobs->$key = $value;
        }
        $jobs->save();
        return $jobs;
    }

    public function updateById($id, $data)
    {
        $jobs = $this->findById($id);
        foreach ($data as $key => $value) {
            $jobs->$key = $value;
        }
        $jobs->save();
        return $jobs;
    }

    public function updateByIdHash($id, $data)
    {
        $jobs = $this->findByIdHash($id);
        foreach ($data as $key => $value) {
            $jobs->$key = $value;
        }
        $jobs->save();
        return $jobs;
    }

    public function deletedById($id)
    {
        $jobs = $this->findById($id);
        return $jobs->delete();
    }

    public function deletedByIdHash($id)
    {
        $jobs = $this->findByIdHash($id);
        return $jobs->delete();
    }
}