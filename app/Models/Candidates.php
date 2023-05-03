<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use App\Traits\HasUUID;

class Candidates extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUUID;
    protected $table = 'candidates';
    protected $fillable = ['name', 'email', 'telepon', 'year', 'job_id'];
    public function jobs()
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
    public function skills()
    {
        return $this->belongsToMany(Skiils::class, 'skills_sets', 'candidate_id', 'skill_id');
        // return $this->belongsToMany(Skiils::class, 'skills_sets', 'candidate_id', 'skill_id')->withPivot('candidate_id','skill_id');
    }
}
