<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait CompensationUnit
{
	use HasTranslations;

	public $translatable = ['name'];

}
