<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = ['slug', 'title', 'body', 'meta_description'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
