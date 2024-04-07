<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'address',
        'sqft',
        'wifi_speed',
        'max_person',
        'price_per_day',
        'attachments',
        'full_support_avaiable',
        'gyn_area_avaiable',
        'mini_cafe_avaiable',
        'cinema_avaiable',
    ];

    // ketika dilempar ke json bisa menjadi array 
    protected $cast = [
        'attachments' => 'array'
    ];

    // ubah fungsii id menjadi slug 
    public function  getRouteKeyName()
    {
        return 'slug';
    }

    // ubah title menjadi slug 
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
