<?php

namespace App\Translatable;

use Spatie\Translatable\HasTranslations;

trait FunctionalArea
{
	use HasTranslations;

	public $translatable = ['name'];

}
