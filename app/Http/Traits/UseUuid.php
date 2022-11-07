<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait UseUuid
{
    protected static function bootUseUuid()
    {
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
