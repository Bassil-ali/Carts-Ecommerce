<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;
    
    public $translatable = ['time_Subscrip'];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Models\Sub_Category', 'sub_category_id');
    }

    public function market()
    {
        return $this->belongsTo('App\Models\Market', 'market_id');
    }

    public function cart_details()
    {
        return $this->belongsTo('App\Models\CartDetail', 'cart_details_id');
    }

    public function scopeWhenSearch($query , $search) 
    {
      return $query->when($search, function ($q) use ($search) {

        return $q->where('amrecan_price' , 'like', "%$search%")
                // ->orWhere('amrecan_price' , 'like', "%$search%")
                // ->orWhere('count_of_buy' , 'like', "%$search%")
                ->orWhere('count_of_buy' , 'like', "%$search%");

      });
    }//end ofscopeWhenSearch

} //end of model
