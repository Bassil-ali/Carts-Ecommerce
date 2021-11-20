<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $guarded = [];
	
	protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        return asset('uploads/payment_images/' . $this->image);

    }//end of get image path

}//end of model
