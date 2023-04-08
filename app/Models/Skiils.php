<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skiils extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'skills';
    protected $fillable = ['name'];

    public function candiates(){
        return $this->belongsToMany(Candidates::class, 'skills_sets', 'skill_id', 'candidate_id ');
    }
}
