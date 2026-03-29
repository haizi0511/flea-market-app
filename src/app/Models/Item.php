<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_image',
        'condition',
        'item_name',
        'brand_name',
        'item_detail',
        'price',
        'user_id',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function mylists()
    {
        return $this->hasMany(Mylist::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
    if (!empty($keyword)) {
        $query->where('item_name', 'like', '%' . $keyword . '%');
    }
        return $query;
    }

    const CONDITIONS = [
        '良好',
        '目立った傷や汚れなし',
        'やや傷や汚れあり',
        '状態が悪い'
    ];
}