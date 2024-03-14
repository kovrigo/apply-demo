<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Article
{
	use HasMediaTrait;

	public function registerMediaCollections()
	{
		$this->addMediaCollection('cover')->singleFile();
		$this->addMediaConversion('thumb')
			->width(config('app.medialibrary_thumb_size'))
			->height(config('app.medialibrary_thumb_size'));
	}

}
