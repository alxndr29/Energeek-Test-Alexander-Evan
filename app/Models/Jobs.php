<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Jobs extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasUUID;
    protected $table = 'jobs';
    protected $fillable = ['name'];
}
