<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait JobState
{
	use HasTranslations;

	public $translatable = ['name'];

}
