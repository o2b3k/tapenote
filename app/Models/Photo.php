<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';

    protected $fillable = [
        'path', 'big_image_path', 'description', 'width', 'height'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'monument_id'
    ];

    public function monument()
    {
        return $this->belongsTo(Monument::class);
    }
}