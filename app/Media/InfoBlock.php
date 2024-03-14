<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait InfoBlock
{
	use HasMediaTrait;

	public function registerMediaCollections()
	{
	    $this->addMediaCollection('photos');
        $this->addMediaConversion('thumb')
              ->width(config('app.medialibrary_thumb_size'))
              ->height(config('app.medialibrary_thumb_size'));        
	}

}
