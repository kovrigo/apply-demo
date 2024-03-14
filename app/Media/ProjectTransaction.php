<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait ProjectTransaction
{
	use HasMediaTrait;

	public function registerMediaCollections()
	{
		$this->addMediaCollection('photos');
	}

}
