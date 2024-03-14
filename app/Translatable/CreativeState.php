<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait CreativeState
{
	use HasTranslations;

	public $translatable = ['name'];

}
