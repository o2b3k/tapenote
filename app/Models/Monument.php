<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monument extends Model
{
    protected $table = 'monuments';

    protected $fillable = [
        'name', 'area', 'data', 'category_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}