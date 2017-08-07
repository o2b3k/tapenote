<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TYPE_CATEGORY = 'CATEGORY';
    const TYPE_PARENT_CATEGORY = 'PARENT_CATEGORY';
    const TYPE_INTERNAL_LINK = 'INTERNAL_LINK';
    const TYPE_EXTERNAL_LINK = 'EXTERNAL_LINK';
    const TYPE_WEB_CONTENT = 'WEB_CONTENT';
    const TYPE_WEB_IMAGE = 'WEB_IMAGE';

    public static function getCategories($withNames)
    {
        if ($withNames) {
            return [
                self::TYPE_CATEGORY        => 'Category',
                self::TYPE_PARENT_CATEGORY => 'Parent category',
                self::TYPE_INTERNAL_LINK   => 'Internal link',
                self::TYPE_EXTERNAL_LINK   => 'External link',
                self::TYPE_WEB_CONTENT     => 'Web content',
                self::TYPE_WEB_IMAGE       => 'Web Image',
            ];
        }

        return [
            self::TYPE_CATEGORY,
            self::TYPE_PARENT_CATEGORY,
            self::TYPE_INTERNAL_LINK,
            self::TYPE_EXTERNAL_LINK,
            self::TYPE_WEB_CONTENT,
            self::TYPE_WEB_IMAGE,
        ];
    }

    protected $table = 'categories';

    protected $fillable = [
        'name', 'data',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'country_id', 'parent_id'
    ];

    protected $guarded = [
        'type',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function monuments()
    {
        return $this->hasMany(Monument::class);
    }
}