<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait Uuidable {

    /**
     * Boot function from laravel.
     */
    public static function bootUuidable() {
        static::creating( function ( $model ) {
            if (empty($model->{$model->getUuidName()})) {
                $model->{$model->getUuidName()} = Str::uuid();
            }
        } );
    }

    public function getUuidName()
    {
        if ($this->uuidKey) {
            return $this->uuidKey;
        } else {
            return 'uuid';
        }
    }
}
