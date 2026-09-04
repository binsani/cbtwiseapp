<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    protected $fillable = ['question', 'answer', 'sort_order', 'is_schema_visible', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_schema_visible' => 'boolean',
            'is_active'         => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
