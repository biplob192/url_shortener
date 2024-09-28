<?php

namespace App\Traits;

use App\Models\Scopes\ActiveScope;

trait IsActive
{
    protected static function bootIsActive()
    {

        static::addGlobalScope( new ActiveScope );

        static::creating( function ( $model ) {
            $model->is_active = 1;
        } );
    }
}
