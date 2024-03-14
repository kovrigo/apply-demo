<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait Profile
{
	use HasMediaTrait;

	public function registerMediaCollections()
    {
        $this->addMediaCollection('cv')->singleFile();
    }

}
