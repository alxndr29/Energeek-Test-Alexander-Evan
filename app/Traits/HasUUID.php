<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUUID()
    {
        static::creating(function (Model $model) {
            if (empty($model->id_hash)) {
                $model->id_hash = Str::orderedUuid()->toString();
            }
        });
    }
}
