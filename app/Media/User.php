<?php

namespace App\Media;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait User
{
	use HasMediaTrait;

	public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

}
