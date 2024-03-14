<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Company
{
	use HasMediaTrait;

	public function registerMediaCollections()
    {
        $this->addMediaCollection('logo')->singleFile();
		$this->addMediaConversion('thumb')
			->width(config('app.medialibrary_thumb_size'))
			->height(config('app.medialibrary_thumb_size'));        
    }

}
