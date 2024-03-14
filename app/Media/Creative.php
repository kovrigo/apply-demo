<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Creative
{
	use HasMediaTrait;

	public function registerMediaCollections()
	{
	    $this->addMediaCollection('creative')->singleFile();
	}

}
