<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Json implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if(empty($value)){
            return new \stdClass();
        }
        return json_decode($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        if(is_object($value) || is_array($value)){
            return json_encode($value);
        }else{
            return $value;
        }
    }

}
