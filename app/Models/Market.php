<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Market extends Model
{
    use HasTranslations;

    protected $guarded = [];
    
    protected $appends = ['image_path'];

    public $translatable = ['name','balance_type'];

    public function user()
    {

        return $this->belongsTo('App\Models\User');

    }

    public function Product()
    {
        return $this->hasMany('App\Models\Product', 'market_id');
    }

    public function CartStore()
    {
        return $this->hasMany('App\Models\CartStore', 'market_id');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Sub_Category', 'sub_categories_id');
    }

    public function getImagePathAttribute()
    {
        return asset('uploads/market_images/' . $this->image);

    } //end of get image path

} //end of model
