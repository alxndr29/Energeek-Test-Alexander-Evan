<?php

namespace App\Interfaces;

use App\Models\Skiils;

interface SkillsInterface
{
    public function getAll($select = [], $search = null, $sortOption = [], $paginateOption = [],  $reformat = null);
    public function findById($id, $method = 'findOrFail');
    public function findByIdHash($id, $withRelations = [], $method = 'firstOrFail');
    public function create($data);
    public function updateById($id, $data);
    public function updateByIdHash($id, $data);
    public function deletedById($id);
    public function deletedByIdHash($id);
}
