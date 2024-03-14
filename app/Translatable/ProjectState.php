<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait ProjectState
{
	use HasTranslations;

	public $translatable = ['name'];

}

