<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'name', 'lat', 'long'
    ];

    protected $guarded = [
        'default',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getDefaultAttribute($value) {
        return (bool) $value;
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function monuments()
    {
        return $this->hasManyThrough(Monument::class, Category::class);
    }
}